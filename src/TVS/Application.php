<?php

namespace TVS;

use Silex\Application as ApplicationSilex;
use TVS\Login\Controller\LoginController;
use TVS\Login\Controller\RouteController;
use TVS\Login\Controller\PrivilegeController;
use Symfony\Component\HttpFoundation\Request;

class Application extends ApplicationSilex {

    public function __construct(array $values = array()) {
        parent::__construct($values);
        $app = $this;

        $app['LoginService'] = function () use($app) {
            $loginService = new Login\Service\LoginService($app['EntityManager'], new Login\Entity\User);
            return $loginService;
        };
        
        $app['RouteService'] = function () use($app) {
            $routeService = new Login\Service\RouteService($app['EntityManager'], new Login\Entity\Route);
            return $routeService;
        };
        
        $app['PrivilegeService'] = function () use($app) {
            $routeService = new Login\Service\PrivilegeService($app['EntityManager'], new Login\Entity\Privilege);
            return $routeService;
        };

        $app->before(function(Request $request) use ($app) {
            if (!$request->get('non_require_authentication')) {
                if(!$app['session']->get('user')){
                    return $app->redirect('/');
                }
            }
        });

        $app->get('/', function () use ($app) {
                    return $app['twig']->render('login/login.twig', []);
                })->bind('login')
                ->value('non_require_authentication', true);

        $app->get('/index', function () use ($app) {
            return $app['twig']->render('template.twig', []);
        })->bind('inicio');

        $app->mount("/login", new LoginController());
        $app->mount("/routes", new RouteController());
        $app->mount("/privileges", new PrivilegeController());
    }

}
