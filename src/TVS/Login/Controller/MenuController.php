<?php

namespace TVS\Login\Controller;

use TVS\Base\Controller\AbstractController;

class MenuController extends AbstractController {

    public function __construct() {
        $this->registros_por_pagina = 5;
        $this->service = 'MenuService';
        $this->form = 'MenuForm';
        $this->bind = 'menu';
        $this->param_view = 'result';
        $this->view_new = 'login/default/default_new.twig';
        $this->view_edit = 'login/default/default_edit.twig';
        $this->view_list = 'login/menu/menu.html.twig';
        $this->titulo = "Menu";
        
    }

    public function connect_extra() {
        
    }

}