<?php

namespace Home\Controller;

class IndexController extends BaseController
{
    public function __construct()
    {
    }

    public function index()
    {
        $this->show(__CONTROLLER__);
    }
}