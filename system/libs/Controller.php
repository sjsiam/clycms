<?php

namespace System\Libs;

class Controller
{

    public function __construct() {}

    public function view($fileName, array $data = [])
    {
        if (!empty($data) && is_array($data)) {
            extract($data);
        }
        $filePath = 'app/views/' . $fileName . '.php';
        if (file_exists($filePath)) {
            include $filePath;
        } else {
            throw new \Exception("View not found: " . $fileName);
        }
    }

    public function model($model)
    {
        $modelPath = 'app/models/' . $model . '.php';
        if (file_exists($modelPath)) {
            include $modelPath;
            return new $model();
        } else {
            throw new \Exception("Model not found: " . $model);
        }
    }
}
