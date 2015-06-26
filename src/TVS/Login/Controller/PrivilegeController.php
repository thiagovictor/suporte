<?php

namespace TVS\Login\Controller;

use TVS\Base\Controller\AbstractController;

class PrivilegeController extends AbstractController {

    public function __construct() {
        $this->registros_por_pagina = 5;
        $this->service = 'PrivilegeService';
        $this->bind = 'privilege';
        $this->param_view = 'result';
        $this->redirect_delete = '/privileges';
        $this->view_new = 'login/default/default_new.twig';
        $this->view_edit = 'login/default/default_edit.twig';
        $this->view_list = 'login/privilege/privilege.twig';
        $this->form_new = 'PrivilegeForm';
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
