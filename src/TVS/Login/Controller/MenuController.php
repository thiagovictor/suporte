<?php

namespace TVS\Login\Controller;

use TVS\Base\Controller\AbstractController;
use TVS\Base\Lib\RepositoryFile;
use Symfony\Component\HttpFoundation\Response;

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
        $app = $this->app;
        $this->controller->get('/display/dinamicmenu', function () use ($app) {
            $result = $app[$this->service]->getMenu();

            return $app['twig']->render('login/menu/dinamic_menu.html.twig', [
                        'result' => $result,
            ]);
        })->bind('DinamicMenu');

        $this->controller->get('/display/preferencemenu', function () use ($app) {
            return $app['twig']->render('login/menu/preference_menu.html.twig', [
                        'user' => $app['session']->get('user'),
            ]);
        })->bind('PreferenceMenu');

        $this->controller->get('/display/preferenceimage/{id}', function ($id) use ($app) {
            $user = $app['LoginService']->find($id);
            return new Response(
                    (new RepositoryFile("../data".$user->getImage()))->getArquivo(), 200, array(
                        'Content-Type' => 'image/jpg',
                        'Content-Disposition' => 'filename="image.jpg"'
                    )
            );   
        })->bind('PreferenceImage');
    }

}
