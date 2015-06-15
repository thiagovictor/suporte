<?php

namespace TVS;

use Silex\Application as ApplicationSilex;
use TVS\Login\Controller\LoginController;

class Application extends ApplicationSilex {

    public function __construct(array $values = array()) {
        parent::__construct($values);
        $app = $this;

        $app['logado'] = false;

        $app['LoginService'] = function () use($app) {
            $loginService = new Login\Service\LoginService($app['EntityManager'], new Login\Entity\User);
            return $loginService;
        };

        $app->get('/', function () use ($app) {
            return $app['twig']->render('login/login.twig', []);
        })->bind('inicio');

        $app->mount("/login", new LoginController());
    }

}
