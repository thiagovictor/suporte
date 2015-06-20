<?php

namespace TVS\Login\Service;

use Doctrine\ORM\EntityManager;
use TVS\Login\Entity\Privilege;
use TVS\Base\Service\AbstractService;

class PrivilegeService extends AbstractService {

    public function __construct(EntityManager $em, Privilege $privilege) {
        parent::__construct($em);
        $this->object = $privilege;
        $this->entity = "TVS\Login\Entity\Privilege";
    }

    public function ajustaData(array $data = array()) {
        $privilegios = [
            'display', 'new', 'edit', 'delete'
        ];
        if (!empty($data["user"])) {
            $repo = $this->em->getRepository("TVS\Login\Entity\User");
            $data["user"] = $repo->findById($data["user"]);
        }
        if (!empty($data["route"])) {
            $repo = $this->em->getRepository("TVS\Login\Entity\Route");
            $data["route"] = $repo->findById($data["route"]);
        }
        foreach ($privilegios as $key => $value) {
            if (!isset($data[$value])) {
                $data[$value] = 0;
                continue;
            }
            $data[$value] = 1;
        }
        return $data;
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
        if ('' == $route['action']) {
            $route['action'] = 'display';
        }
        return $route;
    }

    public function isAllowed($user, $request) {
        $route = $this->mountArrayRoute($request);
        $routeRepository = $this->em->getRepository('TVS\Login\Entity\Route');
        $objectRoute = $routeRepository->findOneByRoute($route['controller']);
        $PrivilegeRepositoty = $this->em->getRepository($this->entity);
        $objectPrivilege = $PrivilegeRepositoty->findOneBy(array('user' => $user, 'route' => $objectRoute));
        $getAction = 'get'.ucfirst($route['action']);
        if($objectPrivilege){
            return $objectPrivilege->$getAction();
        }
        $objectRouteGeneric = $routeRepository->findOneByRoute('*');
        $objectPrivilegeGeneric = $PrivilegeRepositoty->findOneBy(array('user' => $user, 'route' => $objectRouteGeneric)); 
        if($objectPrivilegeGeneric){
            return $objectPrivilegeGeneric->$getAction();
        }
        return false;    
    }

}
