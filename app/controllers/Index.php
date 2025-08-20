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
        $this->view("category", ['test' => 'You Gorib']);
    }
}