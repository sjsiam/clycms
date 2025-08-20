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

    public function select($table)
    {
        $sql = "SELECT * FROM $table";
        $stmt = $this->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}