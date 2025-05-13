<?php
class AdminorderController extends BaseController
{
    private $orderModel;
    private $userModel;
    public function __construct()
    {
        $this->loadModel('OrderModel');
        $this->loadModel('UserModel');

        $this->userModel = new UserModel();
        $this->orderModel = new OrderModel();
    }
    public function index()
    {
        return $this->view("frontend.admin.Adminorder.index", [
            "orderList" => $this->orderModel->getAll_AdminOrder()
        ]);
    }

    public function sort()
    {
        $rawdata = file_get_contents('php://input');
        $selectedStatus = trim($rawdata);
        switch ($selectedStatus) {
            case 'newest':
                return $this->view("frontend.admin.Adminorder.sort", [
                    "orderList" => $this->orderModel->getAll_AdminOrder_newest()
                ]);

            case 'oldest':
                return $this->view("frontend.admin.Adminorder.sort", [
                    "orderList" => $this->orderModel->getAll_AdminOrder_oldest()
                ]);

            case 'total-high':
                return $this->view("frontend.admin.Adminorder.sort", [
                    "orderList" => $this->orderModel->getAll_AdminOrder_total_high()
                ]);

            case 'total-low':
                return $this->view("frontend.admin.Adminorder.sort", [
                    "orderList" => $this->orderModel->getAll_AdminOrder_total_low()
                ]);

            default:
                // Trường hợp không khớp bất kỳ case nào
                return $this->view("frontend.admin.Adminorder.sort", [
                    "orderList" => $this->orderModel->getAll_AdminOrder()
                ]);
        }

    }

    public function delete()
    {
        $idOrder = isset($_POST['orderID']) ? $_POST['orderID'] : '';

        $this->orderModel->updateOrder($idOrder, [
            'hide' => 1
        ]);

        return $this->view("frontend.admin.Adminorder.sort", [
            "orderList" => $this->orderModel->getAll_AdminOrder()
        ]);

    }

    public function changeStatus()
    {
        $orderID = isset($_POST['orderID']) ? $_POST['orderID'] : '';
        $status = isset($_POST['status']) ? $_POST['status'] : '';
        // var_dump($_POST);
        switch ($status) {
            case 'waitConfirm':
                $this->orderModel->updateOrder($orderID, [
                    'status' => 'pending'
                ]);
                break;
            case 'pending':
                $this->orderModel->updateOrder($orderID, [
                    'status' => 'shipping'
                ]);
                break;
            case 'shipping':
                $this->orderModel->updateOrder($orderID, [
                    'status' => 'completed'
                ]);
                break;
        }

        return $this->view("frontend.admin.Adminorder.sort", [
            "orderList" => $this->orderModel->getAll_AdminOrder()
        ]);
    }
    public function cancel()
    {
        $orderID = isset($_POST['orderID']) ? $_POST['orderID'] : '';
        $status = isset($_POST['status']) ? $_POST['status'] : '';
        // var_dump($_POST);
        $this->orderModel->updateOrder($orderID, [
            'status' => 'cancelled'
        ]);

        return $this->view("frontend.admin.Adminorder.sort", [
            "orderList" => $this->orderModel->getAll_AdminOrder()
        ]);
    }

    public function getDetailInfo()
    {
        $customer_query = '
            SELECT U.NAME, U.email, U.phone, U.address
            FROM `ORDER` O JOIN USER U ON O.id_User = U.id_User
            WHERE O.id_Order = ' . $_POST['orderID'] . '
        ';

        $whereID = intval($_POST['orderID']); // Đảm bảo an toàn (tránh SQL Injection)

        $detail_product = "
            SELECT 
                P.name, 
                CONCAT(REPLACE(FORMAT(P.current_price, 0), ',', '.'), 'đ') AS current_price, 
                OD.quantity, 
                CONCAT(REPLACE(FORMAT(P.current_price * OD.quantity, 0), ',', '.'), 'đ') AS sub_total,
                DATE_FORMAT(O.created_at, '%d/%m/%Y') AS created_at,
                CONCAT(REPLACE(FORMAT(O.total_amount, 0), ',', '.'), 'đ') AS total_amount
            FROM order_detail OD 
            JOIN PRODUCT P ON OD.id_Product = P.id_product 
            JOIN `ORDER` O ON O.id_Order = OD.id_Order
            WHERE OD.id_Order = $whereID
        ";

        echo json_encode([
            'customer' => $this->userModel->getByQuery($customer_query),
            'detail_product' => $this->userModel->getByQuery($detail_product)
        ]);
    }
}
?>