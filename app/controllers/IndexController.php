<?php

namespace App\Controllers;

use System\Libs\Controller;

class IndexController extends Controller
{
    public function home() {
        $this->view("home");
    }
}