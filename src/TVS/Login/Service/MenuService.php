<?php

namespace TVS\Login\Service;

use Doctrine\ORM\EntityManager;
use TVS\Login\Entity\Menu;
use TVS\Base\Service\AbstractService;

class MenuService extends AbstractService {

    public function __construct(EntityManager $em, Menu $menu) {
        parent::__construct($em);
        $this->object = $menu;
        $this->entity = "TVS\Login\Entity\Menu";
    }

    public function ajustaData(array $data = array()) {
        return $data;
    }


}
