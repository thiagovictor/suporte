<?php

namespace TVS\Login\Controller;

use TVS\Base\Controller\AbstractController;

class RouteController extends AbstractController {

    public function __construct() {
        $this->registros_por_pagina = 5;
        $this->service = 'RouteService';
        $this->form = 'RouteForm';
        $this->views = 'login/route/route';
        $this->bind = 'route';
        $this->param_view = 'result';
        $this->redirect_delete = '/routes';
        $this->view_default = 'login/default/default';
    }

    public function connect_extra() {
        
    }

}
