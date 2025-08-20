<?php

namespace App\Models;

use System\Libs\Model;

class Category extends Model
{

    public function catlist() {
        $sql = "SELECT * FROM categories";
        $query = $this->db->query($sql);
        return $query->fetchAll();
    }
}
