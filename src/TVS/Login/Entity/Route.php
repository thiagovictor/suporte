<?php

namespace TVS\Login\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="TVS\Login\Entity\RouteRepository")
 * @ORM\Table(name="route")
 */
class Route {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue 
     */
    private $id;

    /**
     * @ORM\Column(name="route", type="string", nullable=false) 
     */
    private $route;

    function getId() {
        return $this->id;
    }

    function getRoute() {
        return $this->route;
    }

    function setId($id) {
        $this->id = $id;
        return $this;
    }

    function setRoute($route) {
        $this->route = $route;
        return $this;
    }
    
    function toArray() {
        return [
            'id'=>  $this->getId(),
            'route' => $this->getRoute()
        ];
    }

}
