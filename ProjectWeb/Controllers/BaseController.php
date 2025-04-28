<?php
class BaseController
{
    const VIEW_FOLDER_NAME = 'Views';
    const MODEL_FOLDER_NAME= 'Models';
    /**
     * Description:
     * + path name: folderName.fileName
     * + Lấy từ sau thư mục Views
     */
    public function view($viewPath, array $data = [])
    {
        foreach($data as $key=>$value){
            $$key=$value;
        }
        return require(self::VIEW_FOLDER_NAME . '/' . str_replace('.', '/', $viewPath) . '.php');
    }

protected function loadModel($modelPath)
{
    require(self::MODEL_FOLDER_NAME . '/' . $modelPath . '.php');
}


}

function view($viewPath, array $data = []) {
    // Chuyển mảng dữ liệu thành các biến động (dynamic variables)
    foreach ($data as $key => $value) {
        $$key = $value;
    }

    // Đưa đường dẫn tới tệp view từ thư mục Views
    require('Views/' . str_replace('.', '/', $viewPath) . '.php');
}
?>

