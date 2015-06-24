<?php

namespace TVS\Base\Controller;

use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

class AbstractController implements ControllerProviderInterface {

    protected $registros_por_pagina = 5;
    protected $service;
    protected $form;
    protected $view_default = null;
    protected $views;
    protected $bind;
    protected $param_view;
    protected $redirect_delete;
    protected $controller;
    protected $app;

    protected function connect_extra() {
        
    }

    protected function getParams() {
        return [];
    }

    private function isPost($request) {
        if ('POST' == $request->getMethod()) {
            return true;
        }
        return false;
    }

    public function connect(Application $app) {
        $this->controller = $app['controllers_factory'];
        $this->app = $app;

        $this->connect_extra();

        $this->controller->get('/', function (Request $request) use ($app) {
            $result = $app[$this->service]->findPagination(0, $this->registros_por_pagina);
            return $app['twig']->render($this->views . '.twig', [$this->param_view => $result, 'page_atual' => 1, 'pagination' => $app[$this->service]->pagination($request, 1, $this->registros_por_pagina)]);
        })->bind($this->bind . '_listar');

        $this->controller->get('/page/{page}', function ($page, Request $request) use ($app) {
            if ($page < 1 or $page > ceil($app[$this->service]->getRows() / $this->registros_por_pagina)) {
                $page = 1;
            }
            $result = $app[$this->service]->findPagination((($page - 1) * $this->registros_por_pagina), $this->registros_por_pagina);
            return $app['twig']->render($this->views . '.twig', [$this->param_view => $result, 'page_atual' => $page, 'pagination' => $app[$this->service]->pagination($request, $page, $this->registros_por_pagina)]);
        })->bind($this->bind . '_listar_pagination');

        $this->controller->match('/new', function (Request $request) use ($app) {
            $form = $app[$this->form];
            $form->handleRequest($request);
            $view = $this->views;
            if ($this->view_default) {
                $view = $this->view_default;
            }
            $serviceManager = $app[$this->service];
            if ($form->isValid()) {
                $data = $form->getData();
                $result = $serviceManager->insert($data);
                return $app['twig']->render($view . '_new.twig', [
                            "success" => $result,
                            "Message" => $serviceManager->getMessage(),
                            "params" => $this->getParams(),
                            "form" => $form->createView(),
                            "route" => $serviceManager->mountArrayRoute($request)
                ]);
            }
            return $app['twig']->render($view . '_new.twig', ["Message" => array(), "params" => $this->getParams(), "form" => $form->createView(), "route" => $serviceManager->mountArrayRoute($request)]);
        })->bind($this->bind . '_new');

        $this->controller->match('/edit/{id}', function ($id, Request $request) use ($app) {
            $form = $app[$this->form];
            $serviceManager = $app[$this->service];
            $route = $serviceManager->mountArrayRoute($request);
            $view = $this->views;
            if ($this->view_default) {
                $view = $this->view_default;
            }
            if ($this->isPost($request)) {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $data = $form->getData();
                    $data["id"] = $id;
                    $serviceManager->update($data);
                    return $app->redirect("/" . $route["controller"]);
                }
                return $app['twig']->render($view . '_edit.twig', ["form" => $form->createView(),"Message" => $serviceManager->getMessage(),"route" => $serviceManager->mountArrayRoute($request)]);
            }
            $result = $serviceManager->find($id);
            $form->setData($result->toArray());
            return $app['twig']->render($view . '_edit.twig', ["form" => $form->createView(),"Message" => $serviceManager->getMessage(),"route" => $serviceManager->mountArrayRoute($request)]);
        })->bind($this->bind . '_edit');

        $this->controller->get('/delete/{id}', function ($id) use ($app) {
            $app[$this->service]->delete($id);
            return $app->redirect($this->redirect_delete);
        })->bind($this->bind . '_delete');


        return $this->controller;
    }

}
