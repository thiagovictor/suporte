<?php

namespace TVS\Base\Controller;

use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

class AbstractController implements ControllerProviderInterface {

    protected $registros_por_pagina = 5;
    protected $service;
    protected $form_edit = null;
    protected $form;
    protected $view_new;
    protected $view_edit;
    protected $view_list;
    protected $bind;
    protected $param_view;
    protected $controller;
    protected $titulo;
    protected $app;

    protected function connect_extra() {
        
    }

    public function connect(Application $app) {
        $this->controller = $app['controllers_factory'];
        $this->app = $app;

        $this->connect_extra();

        //####LISTAGEM INICIAL#######
        $this->controller->get('/', function (Request $request) use ($app) {
            $result = $app[$this->service]->findPagination(0, $this->registros_por_pagina);
            return $app['twig']->render($this->view_list, [
                        $this->param_view => $result,
                        'page_atual' => 1,
                        'titulo' => $this->titulo,
                        'pagination' => $app[$this->service]->pagination($request, 1, $this->registros_por_pagina)
            ]);
        })->bind($this->bind . '_listar');


        //####LISTAGEM PAGINADA#######
        $this->controller->get('/page/{page}', function ($page, Request $request) use ($app) {
            if ($page < 1 or $page > ceil($app[$this->service]->getRows() / $this->registros_por_pagina)) {
                $page = 1;
            }
            $result = $app[$this->service]->findPagination((($page - 1) * $this->registros_por_pagina), $this->registros_por_pagina);
            return $app['twig']->render($this->view_list, [
                        $this->param_view => $result,
                        'page_atual' => $page,
                        'titulo' => $this->titulo,
                        'pagination' => $app[$this->service]->pagination($request, $page, $this->registros_por_pagina)
            ]);
        })->bind($this->bind . '_listar_pagination');

        //####NOVO REGISTRO#######
        $this->controller->match('/new', function (Request $request) use ($app) {
            $form = $app[$this->form];
            $form->handleRequest($request);
            $serviceManager = $app[$this->service];
            if ($form->isValid()) {
                $data = $form->getData();
                $result = $serviceManager->insert($data);
                return $app['twig']->render($this->view_new, [
                            "success" => $result,
                            "Message" => $serviceManager->getMessage(),
                            'titulo' => $this->titulo,
                            "form" => $form->createView(),
                            "route" => $serviceManager->mountArrayRoute($request)
                ]);
            }
            return $app['twig']->render($this->view_new, [
                        "Message" => array(),
                        "form" => $form->createView(),
                        'titulo' => $this->titulo,
                        "route" => $serviceManager->mountArrayRoute($request)
            ]);
        })->bind($this->bind . '_new');

        //####EDITANDO REGISTRO#######
        $this->controller->match('/edit/{id}', function ($id, Request $request) use ($app) {
            $form = $app[$this->form];
            if ($this->form_edit) {
                $form = $app[$this->form_edit];
            }

            $serviceManager = $app[$this->service];
            $route = $serviceManager->mountArrayRoute($request);

            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                $data["id"] = $id;
                $serviceManager->update($data);
                //return $app->redirect($app["url_generator"]->generate($this->bind . '_listar'));
                $result = $app[$this->service]->findPagination(0, $this->registros_por_pagina);
                return $app['twig']->render($this->view_list, [
                            $this->param_view => $result,
                            'page_atual' => 1,
                            'Message' => $serviceManager->getMessage(),
                            'titulo' => $this->titulo,
                            'pagination' => $app[$this->service]->pagination($request, 1, $this->registros_por_pagina)
                ]);
            }
            $result = $serviceManager->find($id);
            $form->setData($result->toArray());
            return $app['twig']->render($this->view_edit, [
                        "form" => $form->createView(),
                        'titulo' => $this->titulo,
                        "Message" => $serviceManager->getMessage(),
                        "route" => $serviceManager->mountArrayRoute($request)
            ]);
        })->bind($this->bind . '_edit');

        //####REMOVE REGISTRO#######
        $this->controller->get('/delete/{id}', function ($id, Request $request) use ($app) {
            $serviceManager = $app[$this->service];
            $serviceManager->delete($id);
            //return $app->redirect($app["url_generator"]->generate($this->bind . '_listar'));
            $result = $app[$this->service]->findPagination(0, $this->registros_por_pagina);
            return $app['twig']->render($this->view_list, [
                        $this->param_view => $result,
                        'page_atual' => 1,
                        'Message' => $serviceManager->getMessage(),
                        'titulo' => $this->titulo,
                        'pagination' => $app[$this->service]->pagination($request, 1, $this->registros_por_pagina)
            ]);
        })->bind($this->bind . '_delete');

        return $this->controller;
    }

}
