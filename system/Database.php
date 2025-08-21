<?php

namespace System\Libs;

use PDO;

class Database extends PDO{
    public function __construct()
    {
        $dsn = 'mysql:host=localhost;dbname=clycms';
        $username = 'root';
        $password = '';

        parent::__construct($dsn, $username, $password);
    }

    public function select($sql, $data = [], $fetchMode = PDO::FETCH_ASSOC)
    {
        $stmt = $this->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll($fetchMode);
    }

    public function insert($table, $data){
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }
}