<?php

namespace TVS\Base\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

abstract class AbstractService {

    protected $em;
    protected $message = array();
    protected $object;
    protected $entity;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function ajustaData(array $data = array()) {
        return $data;
    }

    private function popular(array $data = array()) {
        $data_checked = $this->ajustaData($data);
        foreach ($data_checked as $metodo => $valor) {
            $metodo = 'set' . ucfirst($metodo);
            if (!method_exists($this->object, $metodo)) {
                $this->setMessage("Nao foi possivel converte-lo, verifique os atributos enviados");
                return false;
            }
            $this->object->$metodo($valor);
        }
        return $this->object;
    }

    public function insert(array $data = array()) {
        if ($this->popular($data)) {
            $this->em->persist($this->object);
            $this->em->flush();
            return true;
        }
        return false;
    }
    
    public function update(array $data = array()) {
        if (!isset($data["id"])) {
            $this->setMessage("Parametro :id nao encontrado");
            return false;
        }
        $this->object = $this->em->getReference($this->entity, $data["id"]);
        if (!$this->popular($data)) {
            return false;
        }
        $this->em->persist($this->object);
        $this->em->flush();
        return true;
    }

    public function delete($id) {
        $this->object = $this->em->getReference($this->entity, $id);
        $this->em->remove($this->object);
        try {
            $this->em->flush();
            return true;
        } catch (ForeignKeyConstraintViolationException $ex) {
            $this->setMessage($ex->getMessage());
            return false;
        }
    }

    public function findAll() {
        $repo = $this->em->getRepository($this->entity);
        return $repo->findAll();
    }

    protected function toArray($objects) {
        if (!isset($objects[0])) {
            return array();
        }
        $methods = get_class_methods($objects[0]);
        $prop = array();
        for ($i = 0; $i < sizeof($methods); $i++) {
            if (substr($methods[$i], 0, 3) == 'get') {
                $prop[$methods[$i]] = str_replace('get', '', $methods[$i]);
                continue;
            }
        }

        foreach ($objects as $tag) {
            $data = array();
            foreach ($prop as $key => $value) {
                $object = $tag->$key();
                if (is_object($object)) {
                    $data[$value] = $this->toArray([0 => $object]);
                    continue;
                }
                if (is_array($object)) {
                    $data[$value] = $this->toArray($object);
                    continue;
                }
                $data[$value] = $tag->$key();
            }
            $return[] = $data;
        }
        return $return;
    }
    
    public function fatchPairs() {
        $repo = $this->em->getRepository($this->entity);
        return $repo->fatchPairs();
    }
    
    public function findAllToArray() {
        $repo = $this->em->getRepository($this->entity);
        $objects = $repo->findAll();
        return $this->toArray($objects);
    }

    public function findToArray($id) {
        $repo = $this->em->getRepository($this->entity);
        $object = $repo->find($id);
        return $this->toArray([0 => $object]);
    }

    public function findPagination($firstResult, $maxResults) {
        $repo = $this->em->getRepository($this->entity);
        return $repo->findPagination($firstResult, $maxResults);
    }

    public function getRows() {
        $repo = $this->em->getRepository($this->entity);
        return $repo->getRows();
    }

    public function find($id) {
        $repo = $this->em->getRepository($this->entity);
        return $repo->find($id);
    }

    function getMessage() {
        return $this->message;
    }

    function setMessage($message) {
        $this->message[] = $message;
        return $this;
    }

    public function mountArrayRoute($request) {
        $rotas = ['controller', 'action', 'param'];
        $route = [];
        $params = explode("/", substr($request->getRequestUri(), 1, strlen($request->getRequestUri())));
        foreach ($rotas as $key => $value) {
            if (isset($params[$key])) {
                $route[$value] = $params[$key];
                continue;
            }
            $route[$value] = '';
        }
        if ('' == $route['action'] or $route["action"] == "page") {
            $route['action'] = 'display';
        }
        return $route;
    }

    public function pagination($request, $page_atual, $registros_por_pagina) {
        $numero_paginas = ceil($this->getRows() / $registros_por_pagina);
        $route = $this->mountArrayRoute($request);
        $disabled_prev = '';
        if ($page_atual == 1) {
            $disabled_prev = 'disabled';
        }
        $disabled_next = '';
        if ($page_atual >= $numero_paginas) {
            $disabled_next = 'disabled';
        }
        $link_prev = '';
        if ($page_atual > 1) {
            $page_prev = $page_atual - 1;
            $link = "href='/{$route["controller"]}/page/{$page_prev}'";
        }

        $link_next = '';
        if ($page_atual < $numero_paginas) {
            $page_prev = $page_atual + 1;
            $link = "href='/{$route["controller"]}/page/{$link_next}'";
        }
        $return = '<div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <nav>
                    <ul class="pagination">';
                        for ($i = 1; $i <= $numero_paginas; $i++) {
                            if ($i == 1) {
                                $return .= '<li class="prev ' . $disabled_prev . '"><a ' . $link_prev . '>Anterior</a></li>';
                            }
                            if ($numero_paginas > 0) {
                                $return .= '<li ';
                                if ($page_atual == $i) {
                                    $return .= 'class="active"';
                                }
                                $return .= "><a href='/{$route["controller"]}/page/{$i}'>{$i}</a></li>";
                            }
                            if ($i + 1 > $numero_paginas) {
                                $return .= '<li class="next ' . $disabled_next . '"><a ' . $link_next . '>Pr&oacute;ximo</a></li>';
                            }
                        }
                    $return .= '</ul>
                </nav>
            </div>
            <div class="col-md-4"></div>
        </div>';

        return $return;
    }
    
}
