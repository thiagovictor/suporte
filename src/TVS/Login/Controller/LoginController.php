<?php

namespace TVS\Login\Controller;

use TVS\Base\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends AbstractController {

    public function __construct() {
        $this->registros_por_pagina = 5;
        $this->service = 'LoginService';
        $this->views = 'login/user/user';
        $this->bind = 'user';
        $this->param_view = 'result';
        $this->redirect_delete = '/login';
    }

    public function connect_extra() {
        $app = $this->app;
        $this->controller->post('/autenticar', function (Request $request) use ($app) {
            $user = $app[$this->service]->findByUsernameAndPassword($request->get('user'), $request->get('password'));
            if($user){
//              ADICIONAR USER EM SESS�O
                return $app->redirect('/index');
            }
            return $app['twig']->render('login/login.twig', [$this->param_view  => "Usu&aacute;rio e/ou Senha Incorretos", "user"=>$request->get('user')]);
        })->bind($this->bind . '_autenticar')
          ->value('non_require_authentication', true);
    }

}
