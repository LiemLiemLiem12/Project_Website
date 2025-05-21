<?php
class PaymentController extends BaseController
{
    private $orderModel;
    private $userModel;
    private $productModel;

    public function __construct()
    {
        parent::__construct();
        
        // Load các model cần thiết
        $this->loadModel('OrderModel');
        $this->orderModel = new OrderModel();
        
        $this->loadModel('UserModel');
        $this->userModel = new UserModel();
        
        $this->loadModel('ProductModel');
        $this->productModel = new ProductModel();
    }
    
    /**
     * Xử lý thanh toán qua MoMo
     */
    public function processMomo()
    {
        // Kiểm tra phương thức request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithMessage('index.php?controller=order', 'Phương thức không hợp lệ', 'error');
            return;
        }

        // Lấy thông tin đơn hàng từ session (đã được lưu trong OrderController)
        $orderId = $_SESSION['pending_order_id'] ?? null;
        if (!$orderId) {
            $this->redirectWithMessage('index.php?controller=cart', 'Không tìm thấy thông tin đơn hàng', 'error');
            return;
        }

        $order = $this->orderModel->getOrderWithDetails($orderId);
        if (!$order) {
            $this->redirectWithMessage('index.php?controller=cart', 'Không tìm thấy thông tin đơn hàng', 'error');
            return;
            
        }

        // Cấu hình MoMo API
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = "MOMOQWERTY"; // Mã đối tác MoMo cấp
        $accessKey = "F8BBA842ECF85"; // Access key MoMo cấp
        $secretKey = "K951B6PE1waDMi640xX08PD3vg6EkVlz"; // Secret key MoMo cấp
        $orderInfo = "Thanh toán đơn hàng " . $order['order_number'] . " tại 160STORE";
        $amount = $order['total_amount'];
        $orderId = time() . "_" . $order['id_Order'];
        $redirectUrl = "http://" . $_SERVER['HTTP_HOST'] . "/Project_Website/ProjectWeb/index.php?controller=payment&action=momoCallback";
        $ipnUrl = "http://" . $_SERVER['HTTP_HOST'] . "/Project_Website/ProjectWeb/index.php?controller=payment&action=momoIPN";
        $extraData = base64_encode(json_encode([
            "website_id" => "160STORE",
            "order_id" => $order['id_Order']
        ]));

        // Tạo chữ ký
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $orderId . "&requestType=captureWallet";
        
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        // Tạo dữ liệu gửi đến MoMo
        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => '160STORE',
            'storeId' => '160STORE',
            'requestId' => $orderId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => 'captureWallet',
            'signature' => $signature
        ];

        // Gọi API MoMo
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($data))
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        // Thực hiện gọi API và nhận kết quả
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            // Lưu lỗi vào log
            error_log("MoMo Payment Error: " . $err);
            $this->redirectWithMessage('index.php?controller=order', 'Có lỗi khi kết nối đến cổng thanh toán MoMo', 'error');
            return;
        }

        // Xử lý kết quả từ MoMo
        $responseData = json_decode($response, true);
        if (isset($responseData['payUrl'])) {
            // Lưu thông tin đơn hàng vào session để xử lý callback
            $_SESSION['momo_order_data'] = [
                'order_id' => $order['id_Order'],
                'momo_order_id' => $orderId,
                'amount' => $amount
            ];

            // Cập nhật trạng thái đơn hàng
            $this->orderModel->updateOrder($order['id_Order'], [
                'transaction_id' => $orderId,
                'status' => 'pending'
            ]);

            // Chuyển hướng người dùng đến trang thanh toán MoMo
            header('Location: ' . $responseData['payUrl']);
            exit;
        } else {
            // Lưu lỗi vào log
            error_log("MoMo Payment Error: " . json_encode($responseData));
            $this->redirectWithMessage('index.php?controller=order', 'Có lỗi khi tạo thanh toán MoMo: ' . ($responseData['message'] ?? 'Lỗi không xác định'), 'error');
            return;
        }
    }

    /**
     * Xử lý callback từ MoMo
     */
    public function momoCallback()
    {
        // Kiểm tra thông tin từ MoMo gửi về
        $resultCode = $_GET['resultCode'] ?? -1;
        $message = $_GET['message'] ?? '';
        $orderIdFromMomo = $_GET['orderId'] ?? '';
        $transId = $_GET['transId'] ?? '';
        $extraData = $_GET['extraData'] ?? '';

        // Lấy thông tin đơn hàng từ session
        $orderData = $_SESSION['momo_order_data'] ?? null;
        if (!$orderData) {
            $this->redirectWithMessage('index.php', 'Không tìm thấy thông tin đơn hàng', 'error');
            return;
        }

        // Giải mã extraData
        $decodedExtraData = json_decode(base64_decode($extraData), true);
        $orderId = $decodedExtraData['order_id'] ?? $orderData['order_id'];

        // Kiểm tra kết quả thanh toán
        if ($resultCode == 0) {
            // Thanh toán thành công
            // Cập nhật trạng thái đơn hàng
            $this->orderModel->updateOrder($orderId, [
                'transaction_id' => $transId,
                'status' => 'waitConfirm',
                'payment_status' => 'completed'
            ]);

            // Xóa dữ liệu đơn hàng tạm thời
            unset($_SESSION['momo_order_data']);
            unset($_SESSION['pending_order_id']);

            // Xóa giỏ hàng
            unset($_SESSION['cart']);
            unset($_SESSION['cart_loaded']);

            // Chuyển hướng đến trang thành công
            $_SESSION['last_order_id'] = $orderId;
            header('Location: index.php?controller=order&action=index&order_success=true');
            exit;
        } else {
            // Thanh toán thất bại
            // Cập nhật trạng thái đơn hàng
            $this->orderModel->updateOrder($orderId, [
                'status' => 'cancelled',
                'payment_status' => 'failed',
                // 'note' => $order['note'] . ' | Lỗi thanh toán: ' . $message
            ]);

            // Xóa dữ liệu đơn hàng tạm thời
            unset($_SESSION['momo_order_data']);
            unset($_SESSION['pending_order_id']);

            $this->redirectWithMessage('index.php?controller=cart', 'Thanh toán thất bại: ' . $message, 'error');
            return;
        }
    }

    /**
     * Xử lý IPN (Instant Payment Notification) từ MoMo
     * Đây là endpoint được MoMo gọi đến sau khi xử lý thanh toán
     */
    public function momoIPN()
    {
        // Đặt header JSON
        header('Content-Type: application/json');

        // Nhận dữ liệu từ MoMo
        $jsonData = file_get_contents('php://input');
        $momoData = json_decode($jsonData, true);

        if (!$momoData) {
            echo json_encode(['message' => 'Invalid data']);
            exit;
        }

        // Lấy các thông tin cần thiết
        $resultCode = $momoData['resultCode'] ?? -1;
        $message = $momoData['message'] ?? '';
        $orderIdFromMomo = $momoData['orderId'] ?? '';
        $transId = $momoData['transId'] ?? '';
        $extraData = $momoData['extraData'] ?? '';

        // Giải mã extraData để lấy ID đơn hàng
        $decodedExtraData = json_decode(base64_decode($extraData), true);
        $orderId = $decodedExtraData['order_id'] ?? null;

        if (!$orderId) {
            echo json_encode(['message' => 'Order ID not found']);
            exit;
        }

        // Kiểm tra kết quả thanh toán
        if ($resultCode == 0) {
            // Thanh toán thành công
            // Cập nhật trạng thái đơn hàng
            $this->orderModel->updateOrder($orderId, [
                'transaction_id' => $transId,
                'status' => 'waitConfirm',
                'payment_status' => 'completed'
            ]);

            // Trả về kết quả cho MoMo
            echo json_encode([
                'message' => 'Success',
                'status' => 'success'
            ]);
        } else {
            // Thanh toán thất bại
            // Cập nhật trạng thái đơn hàng
            $this->orderModel->updateOrder($orderId, [
                'status' => 'cancelled',
                'payment_status' => 'failed',
                'note' => 'Lỗi thanh toán: ' . $message
            ]);

            // Trả về kết quả cho MoMo
            echo json_encode([
                'message' => 'Payment failed',
                'status' => 'failed'
            ]);
        }
        exit;
    }

    /**
     * Xử lý thanh toán qua VNPay
     */
    public function processVnpay()
    {
        // Kiểm tra phương thức request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithMessage('index.php?controller=order', 'Phương thức không hợp lệ', 'error');
            return;
        }

        // Lấy thông tin đơn hàng từ session (đã được lưu trong OrderController)
        $orderId = $_SESSION['pending_order_id'] ?? null;
        if (!$orderId) {
            $this->redirectWithMessage('index.php?controller=cart', 'Không tìm thấy thông tin đơn hàng', 'error');
            return;
        }

        $order = $this->orderModel->getOrderWithDetails($orderId);
        if (!$order) {
            $this->redirectWithMessage('index.php?controller=cart', 'Không tìm thấy thông tin đơn hàng', 'error');
            return;
        }

        // Cấu hình VNPay
        $vnp_TmnCode = "VNPAY123"; // Mã website từ VNPay cấp
        $vnp_HashSecret = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456"; // Chuỗi bí mật từ VNPay cấp
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://" . $_SERVER['HTTP_HOST'] . "/Project_Website/ProjectWeb/index.php?controller=payment&action=vnpayCallback";
        
        // Tạo dữ liệu gửi đến VNPay
        $vnp_TxnRef = time() . "_" . $order['id_Order']; // Mã đơn hàng
        $vnp_OrderInfo = "Thanh toán đơn hàng " . $order['order_number'] . " tại 160STORE";
        $vnp_OrderType = "billpayment";
        $vnp_Amount = $order['total_amount'] * 100; // VNPay yêu cầu số tiền * 100
        $vnp_Locale = "vn";
        $vnp_BankCode = "";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        ];

        // Thêm bankcode nếu có
        if (!empty($vnp_BankCode)) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        
        // Sắp xếp dữ liệu theo key
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        // Tạo URL để gửi đến VNPay
        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        // Lưu thông tin đơn hàng vào session để xử lý callback
        $_SESSION['vnpay_order_data'] = [
            'order_id' => $order['id_Order'],
            'vnpay_order_id' => $vnp_TxnRef,
            'amount' => $order['total_amount']
        ];

        // Cập nhật trạng thái đơn hàng
        $this->orderModel->updateOrder($order['id_Order'], [
            'transaction_id' => $vnp_TxnRef,
            'status' => 'pending'
        ]);

        // Chuyển hướng người dùng đến trang thanh toán VNPay
        header('Location: ' . $vnp_Url);
        exit;
    }

    /**
     * Xử lý callback từ VNPay
     */
    public function vnpayCallback()
    {
        // Kiểm tra thông tin từ VNPay gửi về
        $vnp_HashSecret = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456"; // Chuỗi bí mật từ VNPay cấp

        // Lấy các tham số trả về từ VNPay
        $vnp_SecureHash = $_GET['vnp_SecureHash'] ?? '';
        $inputData = [];
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        // Tạo lại chữ ký để kiểm tra
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // Lấy thông tin đơn hàng từ session
        $orderData = $_SESSION['vnpay_order_data'] ?? null;
        if (!$orderData) {
            $this->redirectWithMessage('index.php', 'Không tìm thấy thông tin đơn hàng', 'error');
            return;
        }

        $orderId = $orderData['order_id'];
        $amount = $orderData['amount'] * 100; // VNPay trả về số tiền * 100

        // Kiểm tra chữ ký và kết quả thanh toán
        $vnp_ResponseCode = $_GET['vnp_ResponseCode'] ?? '';
        $vnp_TransactionStatus = $_GET['vnp_TransactionStatus'] ?? '';
        $vnp_TxnRef = $_GET['vnp_TxnRef'] ?? '';
        $vnp_Amount = $_GET['vnp_Amount'] ?? 0;
        $vnp_BankCode = $_GET['vnp_BankCode'] ?? '';

        if ($secureHash == $vnp_SecureHash && $vnp_Amount == $amount && $vnp_TxnRef == $orderData['vnpay_order_id']) {
            if ($vnp_ResponseCode == '00' && $vnp_TransactionStatus == '00') {
                // Thanh toán thành công
                // Cập nhật trạng thái đơn hàng
                $this->orderModel->updateOrder($orderId, [
                    'transaction_id' => $vnp_TxnRef,
                    'payment_bank' => $vnp_BankCode,
                    'status' => 'waitConfirm',
                    'payment_status' => 'completed'
                ]);

                // Xóa dữ liệu đơn hàng tạm thời
                unset($_SESSION['vnpay_order_data']);
                unset($_SESSION['pending_order_id']);

                // Xóa giỏ hàng
                unset($_SESSION['cart']);
                unset($_SESSION['cart_loaded']);

                // Chuyển hướng đến trang thành công
                $_SESSION['last_order_id'] = $orderId;
                header('Location: index.php?controller=order&action=index&order_success=true');
                exit;
            } else {
                // Thanh toán thất bại
                // Cập nhật trạng thái đơn hàng
                $this->orderModel->updateOrder($orderId, [
                    'status' => 'cancelled',
                    'payment_status' => 'failed',
                    'note' => 'Lỗi thanh toán VNPay: ' . $vnp_ResponseCode
                ]);

                // Xóa dữ liệu đơn hàng tạm thời
                unset($_SESSION['vnpay_order_data']);
                unset($_SESSION['pending_order_id']);

                $this->redirectWithMessage('index.php?controller=cart', 'Thanh toán thất bại: Mã lỗi ' . $vnp_ResponseCode, 'error');
                return;
            }
        } else {
            // Chữ ký không hợp lệ hoặc thông tin không khớp
            // Cập nhật trạng thái đơn hàng
            $this->orderModel->updateOrder($orderId, [
                'status' => 'cancelled',
                'payment_status' => 'failed',
                'note' => 'Lỗi xác thực thanh toán VNPay'
            ]);

            // Xóa dữ liệu đơn hàng tạm thời
            unset($_SESSION['vnpay_order_data']);
            unset($_SESSION['pending_order_id']);

            $this->redirectWithMessage('index.php?controller=cart', 'Lỗi xác thực thanh toán', 'error');
            return;
        }
    }

    /**
     * Trợ giúp chuyển hướng với thông báo
     */
    private function redirectWithMessage($url, $message, $type = 'info')
    {
        $_SESSION['flash_message'] = [
            'type' => $type,
            'message' => $message
        ];
        
        header("Location: " . $url);
        exit;
    }
}