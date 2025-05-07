<?php
class BaseModel extends Database
{
    protected $connect;

    public function __construct()
    {
        $this->connect = $this->connect();
    }
    // Lấy tất cả dữ liệu từ bản
    public function all($table, $select = ['*'], $limit, $orderBys = [])
    {
        $orderByString = implode(' ', $orderBys);
        $colums = implode(',', $select);
        if ($orderBys) {
            $sql = "SELECT {$colums} FROM {$table} ORDER BY {$orderByString} LIMIT {$limit}";

        } else {
            $sql = "SELECT {$colums} FROM {$table} LIMIT {$limit}";
        }
        $query = $this->_query(sql: $sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($query)) {
            array_push($data, $row);
        }
        return $data;

    }
    // Lấy dựa vào ID
    public function find($table, $id)
    {
        $sql = "SELECT * FROM {$table} WHERE ID={$id} LIMIT 1";
        $query = $this->_query($sql);
        return mysqli_fetch_assoc($query);

    }
    // Thêm dữ liệu vào bảng
    public function create($table, $data = [])
    {
        $columns = implode(',', array_keys($data));  // Lấy các tên cột từ mảng dữ liệu
        $newValues = array_map(function ($value) {
            return "'" . $value . "'";  // Đảm bảo giá trị được đặt trong dấu nháy đơn
        }, array_values($data));
        $newValues = implode(',', $newValues);  // Nối các giá trị vào nhau thành chuỗi
        // Tạo câu lệnh SQL insert vào bảng
        $sql = "INSERT INTO {$table}({$columns}) VALUES ({$newValues})";
        $this->_query($sql);  // Gọi phương thức thực thi câu lệnh SQL
    }


    // Cập nhật dữ liệu bảng
    public function update($table, $id, $data)
    {
        $dataSets = [];  // Initialize an empty array to store the key-value pairs for updating
        // Loop through the data array to build the key-value pairs for the update query
        foreach ($data as $key => $val) {
            // For each pair, create a string like 'column = value' and add it to the dataSets array
            array_push($dataSets, "{$key} = '{$val}'");
        }
        // Combine all the key-value pairs with commas to create the SET part of the SQL query
        $dataSetString = implode(',', $dataSets);

        // Build the complete SQL query string to update the record in the database
        $sql = "UPDATE {$table} SET {$dataSetString} WHERE id = {$id}";

        // Execute the query
        $this->_query($sql);
    }

    public function updateForProduct($table, $id, $data)
    {
        $dataSets = [];  // Initialize an empty array to store the key-value pairs for updating
        // Loop through the data array to build the key-value pairs for the update query
        foreach ($data as $key => $val) {
            // For each pair, create a string like 'column = value' and add it to the dataSets array
            array_push($dataSets, "{$key} = '{$val}'");
        }
        // Combine all the key-value pairs with commas to create the SET part of the SQL query
        $dataSetString = implode(',', $dataSets);

        // Build the complete SQL query string to update the record in the database
        $sql = "UPDATE {$table} SET {$dataSetString} WHERE id_product = {$id}";

        // Execute the query
        $this->_query($sql);
    }


    // Xóa dữ liệu trong bảng
    public function delete($table, $id)
    {
        $sql = "DELETE FROM {$table} WHERE id = {$id}";
        $this->_query($sql);
    }


    private function _query($sql)
    {
        return mysqli_query($this->connect, $sql);
    }

    public function getByQuery($sql)
    {
        $query = $this->_query($sql);
        $data = [];

        while ($row = mysqli_fetch_assoc($query)) {
            array_push($data, $row);
        }

        return $data;
    }

    public function getScalar($sql)
    {
        $query = $this->_query($sql);
        if ($row = mysqli_fetch_row($query)) {
            return $row[0] ?? 0; // chỉ lấy giá trị đầu tiên của dòng đầu tiên
        }
        return null;
    }

}
?>