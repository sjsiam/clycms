<?php

namespace System\Libs;

class Controller
{

    public function __construct() {}

    public function view($file)
    {
        include 'app/views/' . $file . '.php';
    }
}
