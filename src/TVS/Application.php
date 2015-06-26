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
        
        $app['UserForm'] = function () use($app) {
            return $app['form.factory']->createBuilder(new Login\Form\UserType())->getForm();   
        };
        
        $app['UserFormEdit'] = function () use($app) {
            return $app['form.factory']->createBuilder(new Login\Form\UserEditType())->getForm();   
        };

        $app['RouteService'] = function () use($app) {
            $routeService = new Login\Service\RouteService($app['EntityManager'], new Login\Entity\Route);
            return $routeService;
        };
        $app['RouteForm'] = function () use($app) {
            return $app['form.factory']->createBuilder(new Login\Form\RouteForm())->getForm();   
        };
        
        $app['PrivilegeService'] = function () use($app) {
            $privileService = new Login\Service\PrivilegeService($app['EntityManager'], new Login\Entity\Privilege);
            return $privileService;
        };

        $app->before(function(Request $request) use ($app) {
            if (!$request->get('non_require_authentication')) {
                if (!$app['session']->get('user')) {
                    return $app->redirect('/');
                }
                if (!$app['PrivilegeService']->isAllowed($app['session']->get('user'), $request)) {
                    $app['session']->set('error', "Acesso Negado! Permiss&otilde;es insuficientes.");
                    return $app->redirect('/');
                }
            }
        });

        $app->get('/', function () use ($app) {
                    $params = [];
                    if ($app['session']->get('error')) {
                        if (!empty($app['session']->get('error'))) {
                            $params = ['result' => $app['session']->get('error')];
                            $app['session']->set('error', null);
                        }
                    }
                    return $app['twig']->render('login/login.twig', $params);
                })->bind('login')
                ->value('non_require_authentication', true);

        $app->get('/index', function () use ($app) {
            return $app['twig']->render('template.twig', []);
        })->bind('inicio');

        $app->get('/logout', function() use ($app) {
            $app['session']->clear();
            return $app->redirect('/');
        })->bind('logout');

        $app->mount("/login", new LoginController());
        $app->mount("/routes", new RouteController());
        $app->mount("/privileges", new PrivilegeController());
    }

}
