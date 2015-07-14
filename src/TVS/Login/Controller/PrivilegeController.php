<?php

namespace TVS\Login\Controller;

use TVS\Base\Controller\AbstractController;

class PrivilegeController extends AbstractController {

    public function __construct() {
        $this->registros_por_pagina = 5;
        $this->service = 'PrivilegeService';
        $this->bind = 'privilege';
        $this->param_view = 'result';
        $this->view_new = 'login/default/default_new.twig';
        $this->view_edit = 'login/default/default_edit.twig';
        $this->view_list = 'login/privilege/privilege.twig';
        $this->form = 'PrivilegeForm';
        $this->titulo = "Privil&eacute;gios";
    }

    public function connect_extra() {
        $app = $this->app;
        $this->controller->get('/edit/showall/{id}', function ($id) use ($app) {
            $serviceManager = $app[$this->service];
//            var_dump($serviceManager->privilegeMap($id));
//            exit();

            return $app['twig']->render('login/privilege/showall.twig', [
                'result' => $serviceManager->privilegeMap($id),
                'Message' => $serviceManager->getMessage(),
                'titulo' => $this->titulo,
            ]);
        });
    }

}
