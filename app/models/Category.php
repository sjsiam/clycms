<?php

namespace App\Models;

use System\Libs\Model;

class Category extends Model
{

    public function catlist() {
        return $this->db->select('categories');
    }
}
