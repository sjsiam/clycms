<?php

class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $config = Config::get('database');

        try {
            $this->pdo = new PDO(
                "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4",
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetchAll($sql, $params = [])
    {
        return $this->query($sql, $params)->fetchAll();
    }

    public function fetchOne($sql, $params = [])
    {
        return $this->query($sql, $params)->fetch();
    }
    
    public function fetch($sql, $params = [])
    {
        return $this->query($sql, $params)->fetch();
    }

    public function insert($table, $data)
    {
        $fields = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
        $this->query($sql, $data);

        return $this->pdo->lastInsertId();
    }

    public function update($table, $data, $where, $whereParams = [])
    {
        $fields = [];
        foreach (array_keys($data) as $field) {
            $fields[] = "{$field} = :{$field}";
        }
        $fields = implode(', ', $fields);

        // Convert WHERE clause to use named parameters
        $whereClause = $where;
        $processedWhereParams = [];
        
        if (!empty($whereParams)) {
            $counter = 1;
            foreach ($whereParams as $param) {
                $placeholder = ":where_param_{$counter}";
                $whereClause = preg_replace('/\?/', $placeholder, $whereClause, 1);
                $processedWhereParams[$placeholder] = $param;
                $counter++;
            }
        }

        $sql = "UPDATE {$table} SET {$fields} WHERE {$whereClause}";
        return $this->query($sql, array_merge($data, $processedWhereParams));
    }

    public function delete($table, $where, $params = [])
    {
        // Convert WHERE clause to use named parameters
        $whereClause = $where;
        $processedParams = [];
        
        if (!empty($params)) {
            $counter = 1;
            foreach ($params as $param) {
                $placeholder = ":where_param_{$counter}";
                $whereClause = preg_replace('/\?/', $placeholder, $whereClause, 1);
                $processedParams[$placeholder] = $param;
                $counter++;
            }
        }

        $sql = "DELETE FROM {$table} WHERE {$whereClause}";
        return $this->query($sql, $processedParams);
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
