<?php
class AdminReportModel {
    private $conn;
    
    public function __construct() {
        // Kết nối database
        $this->conn = mysqli_connect('localhost', 'root', '', 'fashion_database');
        mysqli_set_charset($this->conn, "utf8");
        
        if (mysqli_connect_errno()) {
            die("Kết nối database thất bại: " . mysqli_connect_error());
        }
    }
    
    // Lấy thống kê tổng quan
    public function getSummaryStats($startDate = null, $endDate = null) {
        $whereClause = "";
        if ($startDate && $endDate) {
            $whereClause = "WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate'";
        }
        
        // Tổng doanh thu
        $revenueSql = "SELECT SUM(total_amount) as total_revenue FROM `order` $whereClause AND status != 'cancelled'";
        $revenueResult = mysqli_query($this->conn, $revenueSql);
        $revenue = mysqli_fetch_assoc($revenueResult)['total_revenue'] ?: 0;
        
        // Số đơn hàng
        $ordersSql = "SELECT COUNT(*) as total_orders FROM `order` $whereClause";
        $ordersResult = mysqli_query($this->conn, $ordersSql);
        $orders = mysqli_fetch_assoc($ordersResult)['total_orders'] ?: 0;
        
        // Khách hàng mới - đếm những user lần đầu xuất hiện trong tháng được chọn
        $customersSql = "SELECT COUNT(DISTINCT new_customers.id_User) as new_customers
            FROM (
                SELECT DISTINCT o1.id_User
                FROM `order` o1
                WHERE o1.id_User IS NOT NULL 
                AND DATE(o1.created_at) BETWEEN '$startDate' AND '$endDate'
                AND NOT EXISTS (
                    SELECT 1 
                    FROM `order` o2
                    WHERE o2.id_User = o1.id_User
                    AND DATE(o2.created_at) < '$startDate'
                )
            ) as new_customers";
        $customersResult = mysqli_query($this->conn, $customersSql);
        $customers = mysqli_fetch_assoc($customersResult)['new_customers'] ?: 0;
        
        // Tỷ lệ chuyển đổi - số khách hàng có đơn hàng / tổng số lượt truy cập
        $conversionSql = "SELECT COUNT(DISTINCT id_User) as unique_customers 
                         FROM `order` 
                         WHERE id_User IS NOT NULL 
                         AND DATE(created_at) BETWEEN '$startDate' AND '$endDate'";
        $conversionResult = mysqli_query($this->conn, $conversionSql);
        $uniqueCustomers = mysqli_fetch_assoc($conversionResult)['unique_customers'] ?: 0;

        $visits = $this->getVisitCount($startDate, $endDate);
        if ($visits == 0) $visits = 1; // Tránh chia cho 0
        $conversion = round(($uniqueCustomers / $visits) * 100, 1);
        
        // Lấy dữ liệu kỳ trước để so sánh
        $prevPeriod = $this->getPreviousPeriodStats($startDate, $endDate);
        
        return [
            'revenue' => $revenue,
            'orders' => $orders,
            'customers' => $customers,
            'conversion' => $conversion,
            'prevPeriod' => $prevPeriod
        ];
    }
    
    // Lấy số lượng lượt truy cập
    private function getVisitCount($startDate = null, $endDate = null) {
        $whereClause = "";
        if ($startDate && $endDate) {
            $whereClause = "WHERE DATE(visited_at) BETWEEN '$startDate' AND '$endDate'";
        }
        
        $sql = "SELECT COUNT(*) as visit_count FROM visits $whereClause";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_assoc($result)['visit_count'] ?: 0;
    }
    
    // Lấy dữ liệu kỳ trước
    private function getPreviousPeriodStats($startDate, $endDate) {
        if (!$startDate || !$endDate) return [
            'revenue' => 0,
            'orders' => 0,
            'customers' => 0,
            'conversion' => 0
        ];
        
        $daysDiff = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24);
        $prevEndDate = date('Y-m-d', strtotime("$startDate - 1 day"));
        $prevStartDate = date('Y-m-d', strtotime("$prevEndDate - $daysDiff days"));
        
        $whereClause = "WHERE DATE(created_at) BETWEEN '$prevStartDate' AND '$prevEndDate'";
        
        // Tổng doanh thu kỳ trước
        $revenueSql = "SELECT SUM(total_amount) as total_revenue FROM `order` $whereClause AND status != 'cancelled'";
        $revenueResult = mysqli_query($this->conn, $revenueSql);
        $revenue = mysqli_fetch_assoc($revenueResult)['total_revenue'] ?: 0;
        
        // Số đơn hàng kỳ trước
        $ordersSql = "SELECT COUNT(*) as total_orders FROM `order` $whereClause";
        $ordersResult = mysqli_query($this->conn, $ordersSql);
        $orders = mysqli_fetch_assoc($ordersResult)['total_orders'] ?: 0;
        
        // Khách hàng mới kỳ trước
        $customersSql = "SELECT COUNT(DISTINCT new_customers.id_User) as new_customers
            FROM (
                SELECT DISTINCT o1.id_User
                FROM `order` o1
                WHERE o1.id_User IS NOT NULL 
                AND DATE(o1.created_at) BETWEEN '$prevStartDate' AND '$prevEndDate'
                AND NOT EXISTS (
                    SELECT 1 
                    FROM `order` o2
                    WHERE o2.id_User = o1.id_User
                    AND DATE(o2.created_at) < '$prevStartDate'
                )
            ) as new_customers";
        $customersResult = mysqli_query($this->conn, $customersSql);
        $customers = mysqli_fetch_assoc($customersResult)['new_customers'] ?: 0;
        
        // Tỷ lệ chuyển đổi - số khách hàng có đơn hàng / tổng số lượt truy cập
        $conversionSql = "SELECT COUNT(DISTINCT id_User) as unique_customers 
                         FROM `order` 
                         WHERE id_User IS NOT NULL 
                         AND DATE(created_at) BETWEEN '$prevStartDate' AND '$prevEndDate'";
        $conversionResult = mysqli_query($this->conn, $conversionSql);
        $uniqueCustomers = mysqli_fetch_assoc($conversionResult)['unique_customers'] ?: 0;

        $visits = $this->getVisitCount($prevStartDate, $prevEndDate);
        if ($visits == 0) $visits = 1; // Tránh chia cho 0
        $conversion = round(($uniqueCustomers / $visits) * 100, 1);
        
        return [
            'revenue' => $revenue,
            'orders' => $orders,
            'customers' => $customers,
            'conversion' => $conversion
        ];
    }
    
    // Tính phần trăm thay đổi
    public function calculatePercentChange($current, $previous) {
        if ($previous == 0) {
            return ($current > 0) ? 100 : 0; // nếu giá trị trước đó là 0, trả về 100% nếu hiện tại > 0, ngược lại 0%
        }
        return round((($current - $previous) / abs($previous)) * 100, 1);
    }
    
    // Lấy báo cáo doanh số theo ngày
    public function getDailySalesReport($startDate, $endDate, $limit = null) {
        $limitClause = $limit ? "LIMIT $limit" : "";
        
        $sql = "SELECT 
                DATE(o.created_at) as date,
                COUNT(DISTINCT o.id_Order) as order_count,
                SUM(o.total_amount) as revenue,
                SUM(
                    (
                        SELECT SUM(od2.quantity * p.import_price)
                        FROM order_detail od2
                        JOIN product p ON od2.id_Product = p.id_product
                        WHERE od2.id_Order = o.id_Order
                    )
                ) as expense,
                SUM(o.total_amount) - SUM(
                    (
                        SELECT SUM(od2.quantity * p.import_price)
                        FROM order_detail od2
                        JOIN product p ON od2.id_Product = p.id_product
                        WHERE od2.id_Order = o.id_Order
                    )
                ) as profit
            FROM `order` o
            WHERE DATE(o.created_at) BETWEEN '$startDate' AND '$endDate'
              AND o.status != 'cancelled'
            GROUP BY DATE(o.created_at)
            ORDER BY date DESC
            $limitClause";
        
        $result = mysqli_query($this->conn, $sql);
        $data = [];
        
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        
        return $data;
    }
    
    // Tính tổng cho báo cáo doanh số
    public function getTotalSalesReport($startDate, $endDate) {
        $sql = "SELECT 
                COUNT(DISTINCT o.id_Order) as total_orders,
                SUM(o.total_amount) as total_revenue,
                SUM(
                    (
                        SELECT SUM(od2.quantity * p.import_price)
                        FROM order_detail od2
                        JOIN product p ON od2.id_Product = p.id_product
                        WHERE od2.id_Order = o.id_Order
                    )
                ) as total_expense,
                SUM(o.total_amount) - SUM(
                    (
                        SELECT SUM(od2.quantity * p.import_price)
                        FROM order_detail od2
                        JOIN product p ON od2.id_Product = p.id_product
                        WHERE od2.id_Order = o.id_Order
                    )
                ) as total_profit
            FROM `order` o
            WHERE DATE(o.created_at) BETWEEN '$startDate' AND '$endDate'
              AND o.status != 'cancelled'";
        
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_assoc($result) ?: [
            'total_orders' => 0, 
            'total_revenue' => 0, 
            'total_expense' => 0, 
            'total_profit' => 0
        ];
    }
    
    // Lấy top sản phẩm bán chạy
    public function getTopProducts($limit = 5, $startDate = null, $endDate = null) {
        $whereClause = "";
        if ($startDate && $endDate) {
            $whereClause = "AND DATE(o.created_at) BETWEEN '$startDate' AND '$endDate'";
        }
        
        $sql = "SELECT 
                p.name as product_name,
                SUM(od.quantity) as total_sold,
                SUM(od.sub_total) as total_revenue,
                ROUND(RAND() * 3) as return_rate,  -- Mô phỏng tỷ lệ hoàn trả (0-3%)
                IFNULL(AVG(r.rate), 0) as avg_rating
            FROM product p
            JOIN order_detail od ON p.id_product = od.id_Product
            JOIN `order` o ON od.id_Order = o.id_Order AND o.status != 'cancelled'
            LEFT JOIN review r ON p.id_product = r.id_Product
            WHERE 1 $whereClause
            GROUP BY p.id_product
            ORDER BY total_sold DESC
            LIMIT $limit";
        
        $result = mysqli_query($this->conn, $sql);
        $data = [];
        
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        
        return $data;
    }
    
    // Lấy phân bố trạng thái đơn hàng cho biểu đồ tròn
    public function getOrderStatusDistribution($startDate = null, $endDate = null) {
        $whereClause = "";
        if ($startDate && $endDate) {
            $whereClause = "WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate'";
        }
        
        // Lấy tổng số đơn hàng
        $totalSql = "SELECT COUNT(*) as total FROM `order` $whereClause";
        $totalResult = mysqli_query($this->conn, $totalSql);
        $total = mysqli_fetch_assoc($totalResult)['total'];
        
        // Lấy số lượng từng trạng thái
        $sql = "SELECT 
                status,
                COUNT(*) as count
            FROM `order` 
            $whereClause
            GROUP BY status
            ORDER BY count DESC";
        
        $result = mysqli_query($this->conn, $sql);
        $data = [];
        $totalPercentage = 0;
        
        // Lưu tạm thời tất cả trạng thái
        $tempData = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $statusName = '';
            switch ($row['status']) {
                case 'pending': 
                    $statusName = 'Đang xử lý';
                    break;
                case 'shipping': 
                    $statusName = 'Đang giao';
                    break;
                case 'completed': 
                    $statusName = 'Hoàn thành';
                    break;
                case 'cancelled': 
                    $statusName = 'Đã hủy';
                    break;
            }
            
            $percentage = round(($row['count'] / $total) * 100, 1);
            // Không thêm vào tổng nếu là trạng thái đã hủy
            if ($row['status'] !== 'cancelled') {
                $totalPercentage += $percentage;
            }
            
            $tempData[] = [
                'status' => $statusName,
                'count' => $row['count'],
                'percentage' => $percentage,
                'original_status' => $row['status']
            ];
        }
        
        // Xử lý và điều chỉnh phần trăm
        foreach ($tempData as $item) {
            if ($item['original_status'] === 'cancelled') {
                // Đảm bảo tổng = 100% bằng cách gán phần còn lại cho trạng thái đã hủy
                $item['percentage'] = round(100 - $totalPercentage, 1);
            }
            
            $data[] = [
                'status' => $item['status'],
                'count' => $item['count'],
                'percentage' => $item['percentage']
            ];
        }
        
        return $data;
    }
    
    // Lấy dữ liệu doanh thu cho biểu đồ
    public function getRevenueChartData($startDate, $endDate, $limit = null) {
        $limitClause = $limit ? "LIMIT $limit" : "";
        
        $sql = "SELECT 
                DATE(created_at) as date,
                SUM(total_amount) as revenue
            FROM `order`
            WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate'
              AND status != 'cancelled'
            GROUP BY DATE(created_at)
            ORDER BY date DESC
            $limitClause";
        
        $result = mysqli_query($this->conn, $sql);
        $data = [];
        
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        
        return $data;
    }
    
    // Lấy thông báo gần đây
    public function getRecentNotifications($limit = 3) {
        $sql = "SELECT * FROM notifications 
                WHERE hide = 0 
                ORDER BY created_at DESC 
                LIMIT $limit";
        
        $result = mysqli_query($this->conn, $sql);
        $data = [];
        
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        
        return $data;
    }
    
    // Đóng kết nối database khi object bị hủy
    public function __destruct() {
        if ($this->conn) {
            mysqli_close($this->conn);
        }
    }
}
?>