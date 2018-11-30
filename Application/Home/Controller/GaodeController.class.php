<?php

namespace Home\Controller;

class GaodeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function view()
    {
        $this->assign('method', __METHOD__);
        $this->display();
    }
}