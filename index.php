<?php

require __DIR__ . '/vendor/autoload.php';

use App\Controllers\IndexController;

$controller = new IndexController();

$controller->home();