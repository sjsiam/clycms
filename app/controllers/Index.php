<?php

namespace App\Controllers;

use System\Libs\Controller;

class Index extends Controller
{
    public function home()
    {
        $this->view("home");
    }

    public function category()
    {
        $category = $this->model("Category");
        $categories = $category->catlist();
        $this->view("category", ['categories' => $categories]);
    }

    public function addCategory()
    {
        $this->view("add-category");
    }

    public function createCat()
    {
        $category = $this->model("Category");

        $name = $_POST['name'];
        $title = $_POST['title'];

        $data = [
            'name' => $name,
            'title' => $title
        ];

        $res = $category->create($data);
        $mdata = array();
        if ($res == true) {
            $mdata['msg'] = "Category created successfully";
            $mdata['status'] = "success";
        } else {
            $mdata['msg'] = "Category creation failed";
            $mdata['status'] = "error";
        }

        $this->view("add-category", $mdata);
    }
}
