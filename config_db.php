<?php

class ConfigDB
{
    private $host = 'localhost';
    private $db_name = 'gudang_komputer';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function connect()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        return $this->conn;
    }

    public function close() {
        $this->conn->close();
    }

    public function select($table, $where = [])
    {
        $query = "SELECT id, name, price, id_category,id_supplier, stock, created_at FROM $table where deleted_at is null";

        foreach ($where as $key => $value) {
            $query .= " $key '$value'";
        }

//        print_r($query);

        $result = $this->conn->query($query);

        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function update($table, $data, $id)
    {
        $updated_at = date('Y-m-d H:i:s');
        $query = "UPDATE $table SET ";
        foreach ($data as $key => $value) {
            $query .= "$key = '$value', ";
        }
        $query .= "updated_at = '$updated_at' WHERE id='$id'";

//        print_r($query);

        return $this->conn->query($query);
    }
}