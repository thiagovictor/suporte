<?php

namespace TVS\Login\Controller;

use TVS\Base\Controller\AbstractController;
use TVS\Login\Entity\Privilege;

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
        $this->field_search = "route";
    }

    public function connect_extra() {
        $app = $this->app;
        $this->controller->get('/edit/showall/{id}', function ($id) use ($app) {
            $serviceManager = $app[$this->service];
            return $app['twig']->render('login/privilege/showall.twig', [
                        'id' => $id,
                        'result' => $serviceManager->privilegeMap($id),
                        'Message' => $serviceManager->getMessage(),
                        'titulo' => $this->titulo,
            ]);
        })->bind('editPrivilegeAllUser');

        $this->controller->post('/edit/updateAll', function () use ($app) {
            $serviceManager = $app[$this->service];
            $serviceManager->UpdateAll($_POST);
            return $app['twig']->render('login/privilege/showall.twig', [
                        'id' => $_POST['id'],
                        'result' => $serviceManager->privilegeMap($_POST['id']),
                        'Message' => $serviceManager->getMessage(),
                        'titulo' => $this->titulo,
            ]);
        })->bind('updateAllPrivilege');
    }

}
