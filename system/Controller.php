<?php

namespace System;

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
        $modelPath = 'App\\Models\\' . $model;
        if (class_exists($modelPath)) {
            return new $modelPath();
        } else {
            throw new \Exception("Model not found: " . $model);
        }
    }
}
