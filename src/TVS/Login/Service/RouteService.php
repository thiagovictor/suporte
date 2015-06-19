<?php

namespace TVS\Login\Service;

use Doctrine\ORM\EntityManager;
use TVS\Login\Entity\Route;
use TVS\Base\Service\AbstractService;

class RouteService extends AbstractService {

    public function __construct(EntityManager $em, Route $rota) {
        parent::__construct($em);
        $this->object = $rota;
        $this->entity = "TVS\Login\Entity\Route";
    }

    public function ajustaData(array $data = array()) {
        return $data;
    }


}
