<?php

namespace TVS;

use Silex\Application as ApplicationSilex;
use TVS\Login\Controller\LoginController;

class Application extends ApplicationSilex {

    public function __construct(array $values = array()) {
        parent::__construct($values);
        $app = $this;

        $app->get('/', function () use ($app) {
            return $app['twig']->render('template.twig', []);
        })->bind('inicio');

        $app->mount("/login", new LoginController());
      
    }

}
