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
}