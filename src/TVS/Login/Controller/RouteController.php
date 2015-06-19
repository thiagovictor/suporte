<?php

namespace TVS\Login\Controller;

use TVS\Base\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class RouteController extends AbstractController {

    public function __construct() {
        $this->registros_por_pagina = 5;
        $this->service = 'RouteService';
        $this->views = 'login/route/route';
        $this->bind = 'route';
        $this->param_view = 'result';
        $this->redirect_delete = '/routes';
    }

    public function connect_extra() {
//        $app = $this->app;
//        $this->controller->post('/autenticar', function (Request $request) use ($app) {
//            $user = $app[$this->service]->findByUsernameAndPassword($request->get('user'), $request->get('password'));
//            if($user){
//                $app['session']->set('user', $user);
//                return $app->redirect('/index');
//            }
//            return $app['twig']->render('login/login.twig', [$this->param_view  => "Usu&aacute;rio e/ou Senha Incorretos", "user"=>$request->get('user')]);
//        })->bind($this->bind . '_autenticar')
//          ->value('non_require_authentication', true);
    }

}
