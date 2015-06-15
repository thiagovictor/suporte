<?php

namespace TVS\Login\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

abstract class AbstractService {

    protected $em;
    protected $message = array();
    protected $validators = array();
    protected $object;
    protected $entity;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    public function checkValues(array $data = array()) {
        $return = true;
        foreach ($data as $metodo => $valor) {
            if(!$this->checkValidator($metodo, $valor)){
                $return = false;
            }
        }
        return $return;
    }
    
    public function checkValidator($metodo, $valor) {
        foreach ($this->validators as $key => $validator) {
            if ($metodo != $key) {
                continue;
            }
            if (!$validator->isValid($valor)) {
                $this->message[] = strtoupper($key) . " : " . $validator->getMessage();
                return false;
            }
        }
        return true;
    }

    public function ajustaData(array $data = array()) {
        return $data;
    }

    private function popular(array $data = array()) {
        $data_checked = $this->ajustaData($data);
        if (!$this->checkValues($data_checked)) {
            return false;
        }
        $this->posValidation();
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
    
    public function posValidation() {
        
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
        if(!isset($objects[0])){
            return array();
        }
        $methods = get_class_methods($objects[0]);
        $prop = array();
        for ($i=0;$i < sizeof($methods); $i++){
            if(substr($methods[$i],0,3) == 'get'){
                $prop[$methods[$i]] = str_replace('get', '', $methods[$i]);
                continue;
            }
        }
        
        foreach ($objects as $tag) {
            $data = array();
            foreach ($prop as $key => $value) {
                $object = $tag->$key();
                if(is_object($object)){
                    $data[$value] = $this->toArray([0=>$object]);
                    continue;
                }
                if(is_array($object)){
                   $data[$value] = $this->toArray($object);
                    continue;
                }
                $data[$value] = $tag->$key(); 
            }
            $return[] = $data;
        }
        return $return;
    }
    
    public function findAllToArray() {
        $repo = $this->em->getRepository($this->entity);
        $objects = $repo->findAll();
        return $this->toArray($objects);
        
    }
    
    public function findToArray($id) {
        $repo = $this->em->getRepository($this->entity);
        $object = $repo->find($id);
        return $this->toArray([0=>$object]);
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

    function setValidators($indice, $validator) {
        $this->validators[$indice] = $validator;
        return $this;
    }

    function setArrayValidators(array $validators) {
        $this->validators = $validators;
        return $this;
    }

}
