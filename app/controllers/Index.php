<?php

namespace App\Controllers;

use System\Libs\Controller;

class Index extends Controller
{
    public function home() {
        $this->view("home");
    }

    public function category() {
        $category = $this->model("Category");
        $categories = $category->catlist();
        $this->view("category", ['categories' => $categories]);
    }
}