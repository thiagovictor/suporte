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
        echo $request->get('user')." | ".$request->get('password');
        exit();
        //$result = $app[$this->service]->findByUsernameAndPassword($request->get('user'), $request->get('password'));
            
            
            //return $app['twig']->render($this->views.'.twig', [$this->param_view  => $result, 'page_atual' => 1, 'numero_paginas' => ceil($app[$this->service]->getRows() / $this->registros_por_pagina)]);
        })->bind($this->bind.'_autenticar');
    }
}
