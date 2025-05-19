<?php
class BaseController
{
    const VIEW_FOLDER_NAME = 'Views';
    const MODEL_FOLDER_NAME = 'Models';
    /**
     * Description:
     * + path name: folderName.fileName
     * + Lấy từ sau thư mục Views
     */
    public function __construct()
    {
        $this->loadModel('CategoryModel');
        $this->categoryModel = new CategoryModel();
    }
    public function view($viewPath, array $data = [])
    {
        if (!isset($data['headerCategories'])) {
            $data['headerCategories'] = $this->getHeaderCategories();
        }
        foreach ($data as $key => $value) {
            $$key = $value;
        }
        return require(self::VIEW_FOLDER_NAME . '/' . str_replace('.', '/', $viewPath) . '.php');
    }
    protected function getHeaderCategories()
    {
        return $this->categoryModel->getCategoriesForMenu();
    }
    protected function loadModel($modelPath)
    {
        $filePath = self::MODEL_FOLDER_NAME . '/' . $modelPath . '.php';
        // Chỉ load file nếu lớp chưa tồn tại
        $className = str_replace('.php', '', basename($modelPath));
        if (!class_exists($className)) {
            require($filePath);
        }
    }
    /**
     * Kiểm tra trạng thái đăng nhập
     * @return bool
     */
    protected function isLoggedIn()
    {
        return isset($_SESSION['user']);
    }

}

function view($viewPath, array $data = [])
{
    // Chuyển mảng dữ liệu thành các biến động (dynamic variables)
    foreach ($data as $key => $value) {
        $$key = $value;
    }

    // Đưa đường dẫn tới tệp view từ thư mục Views
    require('Views/' . str_replace('.', '/', $viewPath) . '.php');
}

?>