<?php
class AdminDashboardController extends BaseController
{
    private $orderModel;
    private $userModel;
    public function __construct()
    {
        $this->loadModel("UserModel");
        $this->loadModel("OrderModel");
        $this->orderModel = new OrderModel();
        $this->userModel = new UserModel();
    }
    public function index()
    {
        $userByMonthDisplay = $this->userModel->getNumByMonth(date('m'));
        $currentUser = $this->userModel->getNumByMonth(date('m'));
        $previousUser = $this->userModel->getNumByMonth(date('m') - 1);
        $percentageUser = $this->calPercentage($currentUser, $previousUser);

        // Tính order
        $orderByMonthDisplay = $this->orderModel->getByMonth(date('m'));
        $currentOrder = $this->orderModel->getByMonth(date('m'));
        $previousOrder = $this->orderModel->getByMonth(date('m') - 1);
        $percentageOrder = $this->calPercentage($currentOrder, $previousOrder);

        // Tính doanh thu
        $revenueByMonthDisplay = $this->shortageNumber($this->orderModel->getRevenue(date('m')));
        $currentRevenue = $this->orderModel->getRevenue(date('m'));
        $previousRevenue = $this->orderModel->getRevenue(date('m') - 1);
        $percentageRevenue = $this->calPercentage($currentRevenue, $previousRevenue);
        return view(
            "frontend.admin.Admindashboard.index",
            [
                "orderNumByMonth" => $orderByMonthDisplay,
                "orderPercentage" => $percentageOrder,
                "revenueByMonth" => $revenueByMonthDisplay,
                "revenuePercentage" => $percentageRevenue,
                "userByMonthDisplay" => $userByMonthDisplay,
                "userPercentage" => $percentageUser
            ]
        );
    }

    public function shortageNumber($num): string
    {
        if ($num >= 1000000000) {
            return number_format($num / 1000000000, 2) . "B"; // 2 chữ số sau dấu thập phân
        }
        if ($num >= 1000000) {
            return number_format($num / 1000000, 2) . "M"; // 2 chữ số sau dấu thập phân
        }
        if ($num >= 1000) {
            return number_format($num / 1000, 2) . "K"; // 2 chữ số sau dấu thập phân
        }

        return (string) $num; // Trả về số gốc nếu không đáp ứng điều kiện nào
    }

    public function calPercentage(float $current, float $previous)
    {
        if ($previous == 0) {
            return 200;
        } else {
            return round($current / $previous * 100);
        }
    }

}
?>