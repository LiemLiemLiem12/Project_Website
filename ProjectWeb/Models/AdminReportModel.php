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
        
        // Khách hàng mới
        $customersSql = "SELECT COUNT(*) as new_customers FROM `user` $whereClause";
        $customersResult = mysqli_query($this->conn, $customersSql);
        $customers = mysqli_fetch_assoc($customersResult)['new_customers'] ?: 0;
        
        // Tỷ lệ chuyển đổi (giả định)
        $visits = $this->getVisitCount($startDate, $endDate);
        $conversion = $visits > 0 ? round(($orders / $visits) * 100, 1) : 0;
        
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
        $customersSql = "SELECT COUNT(*) as new_customers FROM `user` $whereClause";
        $customersResult = mysqli_query($this->conn, $customersSql);
        $customers = mysqli_fetch_assoc($customersResult)['new_customers'] ?: 0;
        
        // Tỷ lệ chuyển đổi kỳ trước
        $visits = $this->getVisitCount($prevStartDate, $prevEndDate);
        $conversion = $visits > 0 ? round(($orders / $visits) * 100, 1) : 0;
        
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
                DATE(created_at) as date,
                COUNT(*) as order_count,
                SUM(total_amount) as revenue,
                ROUND(SUM(total_amount) * 0.65) as expense,  -- Giả định chi phí là 65% doanh thu
                ROUND(SUM(total_amount) * 0.35) as profit    -- Giả định lợi nhuận là 35% doanh thu
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
    
    // Tính tổng cho báo cáo doanh số
    public function getTotalSalesReport($startDate, $endDate) {
        $sql = "SELECT 
                COUNT(*) as total_orders,
                SUM(total_amount) as total_revenue,
                ROUND(SUM(total_amount) * 0.65) as total_expense,
                ROUND(SUM(total_amount) * 0.35) as total_profit
            FROM `order`
            WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate'
              AND status != 'cancelled'";
        
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
        
        $sql = "SELECT 
                status,
                COUNT(*) as count
            FROM `order` 
            $whereClause
            GROUP BY status";
        
        $result = mysqli_query($this->conn, $sql);
        $data = [];
        
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
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