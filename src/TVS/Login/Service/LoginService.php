<?php

namespace TVS\Login\Service;

use Doctrine\ORM\EntityManager;
use TVS\Login\Entity\User;
use TVS\Login\Service\AbstractService;

class LoginService extends AbstractService {

    public function __construct(EntityManager $em, User $user) {
        parent::__construct($em);
        $this->object = $user;
        $this->entity = "TVS\Login\Entity\User";
    }

    public function ajustaData(array $data = array()) {
        if($data["password"] == 'xxxxxx'){
            unset($data["password"]);
        }
        if(isset($data["ativo"])){
            $data["ativo"] = 1;
            return $data;
        }
        $data["ativo"] = 0;
        return $data;
    }

}
