<?php

namespace TVS\Base\Controller;

use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

class AbstractController implements ControllerProviderInterface {

    protected $registros_por_pagina = 5;
    protected $service;
    protected $views;
    protected $bind;
    protected $param_view;
    protected $redirect_delete;
    protected $controller;
    protected $app;

    protected function connect_extra() {    
    }
    public function connect(Application $app) {
        $this->controller = $app['controllers_factory'];
        $this->app = $app;
        
        $this->connect_extra();
        
        $this->controller->get('/', function () use ($app) {
            $result = $app[$this->service]->findPagination(0, $this->registros_por_pagina);
            return $app['twig']->render($this->views.'.twig', [$this->param_view  => $result, 'page_atual' => 1, 'numero_paginas' => ceil($app[$this->service]->getRows() / $this->registros_por_pagina)]);
        })->bind($this->bind.'_listar');

        $this->controller->get('/page/{page}', function ($page) use ($app) {
            if ($page < 1 or $page > ceil($app[$this->service]->getRows() / $this->registros_por_pagina)) {
                $page = 1;
            }
            $result = $app[$this->service]->findPagination((($page - 1) * $this->registros_por_pagina), $this->registros_por_pagina);
            return $app['twig']->render($this->views.'.twig', [$this->param_view  => $result, 'page_atual' => $page, 'numero_paginas' => ceil($app[$this->service]->getRows() / $this->registros_por_pagina)]);
        })->bind($this->bind.'_listar_pagination');

        $this->controller->get('/new', function () use ($app) {
            return $app['twig']->render($this->views.'_new.twig', ["Message" => array()]);
        })->bind($this->bind.'_new');

        $this->controller->post('/new', function (Request $request) use ($app) {
            $serviceManager = $app[$this->service];
            $result = $serviceManager->insert($request->request->all());
            return $app['twig']->render($this->views.'_new.twig', ["success" => $result, "Message" => $serviceManager->getMessage()]);
        })->bind($this->bind.'_new_post');

        $this->controller->get('/edit/{id}', function ($id) use ($app) {
            $result = $app[$this->service]->find($id);
            return $app['twig']->render($this->views.'_edit.twig', [$this->param_view  => $result, "Message" => []]);
        })->bind($this->bind.'_edit');

        $this->controller->post('/edit', function (Request $request) use ($app) {
            $serviceManager = $app[$this->service];
            $serviceManager->update($request->request->all());
            $result = $serviceManager->find($request->get("id"));
            return $app['twig']->render($this->views.'_edit.twig', [$this->param_view  => $result, "Message" => $serviceManager->getMessage()]);
        })->bind($this->bind.'_edit_post');

        $this->controller->get('/delete/{id}', function ($id) use ($app) {
            $app[$this->service]->delete($id);
            return $app->redirect($this->redirect_delete);
        })->bind($this->bind.'_delete');
        

        return $this->controller;
    }

}
