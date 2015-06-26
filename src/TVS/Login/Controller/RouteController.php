<?php

namespace TVS\Login\Controller;

use TVS\Base\Controller\AbstractController;

class RouteController extends AbstractController {

    public function __construct() {
        $this->registros_por_pagina = 5;
        $this->service = 'RouteService';
        $this->form = 'RouteForm';
        $this->bind = 'route';
        $this->param_view = 'result';
        $this->redirect_delete = '/routes';
        $this->view_new = 'login/default/default_new.twig';
        $this->view_edit = 'login/default/default_edit.twig';
        $this->view_list = 'login/route/route.twig';
    }

    public function connect_extra() {
        
    }

}
