<?php

namespace System;


class Model
{
    protected $db;
    protected $table;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function create($data){
        return $this->db->insert($this->table, $data);
    }
}
