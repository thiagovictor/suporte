<?php

namespace TVS\Login\Service;

use Doctrine\ORM\EntityManager;
use TVS\Login\Entity\Privilege;
use TVS\Base\Service\AbstractService;
use TVS\Application;

class PrivilegeService extends AbstractService {

    public function __construct(EntityManager $em, Privilege $privilege, Application $app) {
        parent::__construct($em, $app);
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
            if ($data[$value]) {
                $data[$value] = 1;
                continue;
            }
            $data[$value] = 0;
        }
        return $data;
    }

    public function privilegeMap($id = null) {
        $rotas = $this->app["RouteService"]->findAll();
        $user = $this->app["LoginService"]->find($id);
        $map = array();
        if (!$user) {
            return $map;
        }
        foreach ($rotas as $rota) {
            $map[$rota->getRoute()] = $this->getPrivilege($rota, $user);
        }


        return $map;
    }

    public function getPrivilege(\TVS\Login\Entity\Route $rota, $user) {
        $privilegeRepositoty = $this->em->getRepository('TVS\Login\Entity\Privilege');
        $privilege = $privilegeRepositoty->findOneBy(array('user' => $user, 'route' => $rota));
        if (!$privilege) {
            return [
                'id'=>$rota->getId(),
                'display' => false,
                'new' => false,
                'edit' => false,
                'delete' => false
            ];
        }
        return $privilege->getPermission();
    }

}
