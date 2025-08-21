<?php

namespace App\Models;

use System\Libs\Model;

class Category extends Model
{

    protected $table = 'categories';

    public function catlist() {
        $sql = "SELECT * FROM categories";
        return $this->db->select($sql);
    }
}
