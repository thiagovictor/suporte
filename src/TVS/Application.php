<?php

namespace TVS;

use Silex\Application as ApplicationSilex;
use TVS\Login\Controller\LoginController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Request;

class Application extends ApplicationSilex {

    public function __construct(array $values = array()) {
        parent::__construct($values);
        $app = $this;

        $app['logado'] = false;


        $app['LoginService'] = function () use($app) {
            $loginService = new Login\Service\LoginService($app['EntityManager'], new Login\Entity\User);
            return $loginService;
        };

        $app->before(function(Request $request) use ($app) {
            if (!$app['request']->get('non_require_authentication')) {
                //echo $requestget->RequestUri();
                return $app->redirect('/');
            }
        });

        $app->get('/', function () use ($app) {
                    return $app['twig']->render('login/login.twig', []);
                })->bind('inicio')
                ->value('non_require_authentication', true);

        $app->mount("/login", new LoginController());
    }

}
