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
        // Mặc định lấy dữ liệu 30 ngày gần nhất
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime('-30 days'));
        
        // Kiểm tra nếu có ngày được chọn từ form
        if (isset($_GET['start_date']) && isset($_GET['end_date']) && !empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $startDate = $_GET['start_date'];
            $endDate = $_GET['end_date'];
        }
        
        // Lấy dữ liệu từ model
        $summaryStats = $this->reportModel->getSummaryStats($startDate, $endDate);
        $salesReport = $this->reportModel->getDailySalesReport($startDate, $endDate);
        $salesTotal = $this->reportModel->getTotalSalesReport($startDate, $endDate);
        $topProducts = $this->reportModel->getTopProducts(5, $startDate, $endDate);
        
        // Dữ liệu cho biểu đồ
        $revenueChartData = $this->reportModel->getRevenueChartData($startDate, $endDate);
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