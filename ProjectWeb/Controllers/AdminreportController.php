<?php
// Khai báo đường dẫn tới model
require_once './Models/AdminReportModel.php';

class AdminreportController {
    private $reportModel;
    
    public function __construct() {
        // Khởi tạo đối tượng model
        
        $this->reportModel = new AdminReportModel();
    }
    
    // Xử lý hiển thị trang báo cáo
    public function index() {
        // Kiểm tra nếu có tháng và năm được chọn
        if (isset($_GET['month']) && isset($_GET['year'])) {
            $month = intval($_GET['month']);
            $year = intval($_GET['year']);
            
            // Tính ngày đầu và cuối tháng
            $startDate = date("Y-m-d", mktime(0, 0, 0, $month, 1, $year));
            $endDate = date("Y-m-t", mktime(0, 0, 0, $month, 1, $year));
            
            // Cập nhật lại hidden fields
            $_GET['start_date'] = $startDate;
            $_GET['end_date'] = $endDate;
        } else {
            // Mặc định lấy dữ liệu tháng hiện tại
            $currentMonth = date('n');
            $currentYear = date('Y');
            $startDate = date("Y-m-01"); // Ngày đầu tháng hiện tại
            $endDate = date("Y-m-t");    // Ngày cuối tháng hiện tại
            
            // Cập nhật lại hidden fields
            $_GET['start_date'] = $startDate;
            $_GET['end_date'] = $endDate;
            $_GET['month'] = $currentMonth;
            $_GET['year'] = $currentYear;
        }
        
        // Lấy dữ liệu từ model
        $summaryStats = $this->reportModel->getSummaryStats($startDate, $endDate);
        $salesReport = $this->reportModel->getDailySalesReport($startDate, $endDate); // Bỏ limit để hiển thị tất cả dữ liệu
        $salesTotal = $this->reportModel->getTotalSalesReport($startDate, $endDate);
        $topProducts = $this->reportModel->getTopProducts(5, $startDate, $endDate);
        
        // Dữ liệu cho biểu đồ
        $revenueChartData = $this->reportModel->getRevenueChartData($startDate, $endDate, 10); // Giới hạn 10 mục cho biểu đồ
        $orderStatusData = $this->reportModel->getOrderStatusDistribution($startDate, $endDate);
        
        // Tính toán phần trăm thay đổi so với kỳ trước
        $currentStats = $summaryStats;
        $previousStats = $summaryStats['prevPeriod'];
        
        $changes = [
            'revenue' => $this->reportModel->calculatePercentChange($currentStats['revenue'], $previousStats['revenue']),
            'orders' => $this->reportModel->calculatePercentChange($currentStats['orders'], $previousStats['orders']),
            'customers' => $this->reportModel->calculatePercentChange($currentStats['customers'], $previousStats['customers']),
            'conversion' => $this->reportModel->calculatePercentChange($currentStats['conversion'], $previousStats['conversion'])
        ];
        
        // Lấy thông báo gần đây
        // $notifications = $this->reportModel->getRecentNotifications();
        
        // Chuẩn bị dữ liệu cho biểu đồ
        // Biểu đồ doanh thu
        $chartLabels = [];
        $chartValues = [];
        foreach (array_reverse($revenueChartData) as $item) {
            $chartLabels[] = date('d/m', strtotime($item['date']));
            $chartValues[] = round($item['revenue'] / 1000000, 1); // Đổi sang đơn vị triệu
        }
        
        // Biểu đồ trạng thái đơn hàng
        $orderStatusLabels = [];
        $orderStatusCounts = [];
        $orderStatusColors = [
            'completed' => '#1dd1a1',
            'shipping' => '#4ca3ff',
            'cancelled' => '#ff6b6b',
            'pending' => '#feca57'
        ];
        
        foreach ($orderStatusData as $status) {
            $statusName = '';
            switch ($status['status']) {
                case 'completed': $statusName = 'Hoàn thành'; break;
                case 'shipping': $statusName = 'Đang giao'; break;
                case 'cancelled': $statusName = 'Đã hủy'; break;
                case 'pending': $statusName = 'Đang xử lý'; break;
            }
            $orderStatusLabels[] = $statusName;
            $orderStatusCounts[] = $status['count'];
        }
        
        // Chuyển dữ liệu sang JSON để sử dụng trong JavaScript
        $chartData = json_encode([
            'labels' => $chartLabels,
            'values' => $chartValues
        ]);
        
        $pieChartData = json_encode([
            'labels' => $orderStatusLabels,
            'values' => $orderStatusCounts,
            'colors' => array_values($orderStatusColors)
        ]);
        
        // Hiển thị view
        include('./Views/frontend/admin/AdminReport/index.php');
    }
    
    // Phương thức xử lý AJAX request cho dữ liệu biểu đồ
    public function getChartData() {
        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        
        $revenueData = $this->reportModel->getRevenueChartData($startDate, $endDate);
        $orderStatusData = $this->reportModel->getOrderStatusDistribution($startDate, $endDate);
        
        // Chuẩn bị dữ liệu biểu đồ
        $chartLabels = [];
        $chartValues = [];
        foreach (array_reverse($revenueData) as $item) {
            $chartLabels[] = date('d/m', strtotime($item['date']));
            $chartValues[] = round($item['revenue'] / 1000000, 1);
        }
        
        $orderStatusLabels = [];
        $orderStatusCounts = [];
        
        foreach ($orderStatusData as $status) {
            $statusName = '';
            switch ($status['status']) {
                case 'completed': $statusName = 'Hoàn thành'; break;
                case 'shipping': $statusName = 'Đang giao'; break;
                case 'cancelled': $statusName = 'Đã hủy'; break;
                case 'pending': $statusName = 'Đang xử lý'; break;
            }
            $orderStatusLabels[] = $statusName;
            $orderStatusCounts[] = $status['count'];
        }
        
        $response = [
            'revenueChart' => [
                'labels' => $chartLabels,
                'data' => $chartValues
            ],
            'orderStatusChart' => [
                'labels' => $orderStatusLabels,
                'data' => $orderStatusCounts
            ]
        ];
        
        // Trả về dữ liệu JSON
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
?>