<?php

namespace TVS\Login\Controller;

use TVS\Base\Controller\AbstractController;

class PrivilegeController extends AbstractController {

    public function __construct() {
        $this->registros_por_pagina = 5;
        $this->service = 'PrivilegeService';
        $this->views = 'login/privilege/privilege';
        $this->bind = 'privilege';
        $this->param_view = 'result';
        $this->redirect_delete = '/privilege';
    }

    public function connect_extra() {
        
    }
    
    public function getParams() {
        $app = $this->app;
        $result = [];
        $result["users"] = $app['LoginService']->findAll();
        $result["routes"] = $app['RouteService']->findAll();
        return $result;       
    }

}
