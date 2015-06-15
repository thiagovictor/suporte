<?php

namespace TVS\Login\Controller;

use Silex\ControllerProviderInterface;
use Silex\Application;

class LoginController implements ControllerProviderInterface {
    public function connect(Application $app) {
        $controller = $app['controllers_factory'];

        $controller->get('/', function () use ($app) {
           return ''; 
        })->bind('login');
        
      
        return $controller;
    }

}
