<?php

class PublicController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->theme = Config::get('app.theme', 'default');
    }

    public function home()
    {
        $this->renderTheme('index');
    }
}
