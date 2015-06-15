<?php

namespace TVS\Login\Controller;

use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Silex\Application;

class LoginController implements ControllerProviderInterface {

    private $registros_por_pagina = 5;

    public function connect(Application $app) {
        $controller = $app['controllers_factory'];

        $controller->get('/', function () use ($app) {
            $result = $app['LoginService']->findPagination(0, $this->registros_por_pagina);
            return $app['twig']->render('login/user/user.twig', ['users' => $result, 'page_atual' => 1, 'numero_paginas' => ceil($app['LoginService']->getRows() / $this->registros_por_pagina)]);
        })->bind('users_listar');

        $controller->get('/page/{page}', function ($page) use ($app) {
            if ($page < 1 or $page > ceil($app['LoginService']->getRows() / $this->registros_por_pagina)) {
                $page = 1;
            }
            $result = $app['LoginService']->findPagination((($page - 1) * $this->registros_por_pagina), $this->registros_por_pagina);
            return $app['twig']->render('login/user/user.twig', ['users' => $result, 'page_atual' => $page, 'numero_paginas' => ceil($app['LoginService']->getRows() / $this->registros_por_pagina)]);
        })->bind('user_listar_pagination');

        $controller->get('/novo', function () use ($app) {
            return $app['twig']->render('login/user/user_novo.twig', ["Message" => array()]);
        })->bind('user_novo');

        $controller->post('/novo', function (Request $request) use ($app) {
            $serviceManager = $app['LoginService'];
            $result = $serviceManager->insert($request->request->all());
            return $app['twig']->render('login/user/user_novo.twig', ["success" => $result, "Message" => $serviceManager->getMessage()]);
        })->bind('user_novo_post');

        $controller->get('/edit/{id}', function ($id) use ($app) {
            $result = $app['LoginService']->find($id);
            return $app['twig']->render('login/user/user_edit.twig', ["user" => $result, "Message" => []]);
        })->bind('user_edit');

        $controller->post('/edit', function (Request $request) use ($app) {
            $serviceManager = $app['LoginService'];
            $serviceManager->update($request->request->all());
            $result = $serviceManager->find($request->get("id"));
            return $app['twig']->render('login/user/user_edit.twig', ["user" => $result, "Message" => $serviceManager->getMessage()]);
        })->bind('user_edit_post');

        $controller->get('/delete/{id}', function ($id) use ($app) {
            $app['LoginService']->delete($id);
            return $app->redirect("/login");
        })->bind('user_delete');


        return $controller;
    }

}
