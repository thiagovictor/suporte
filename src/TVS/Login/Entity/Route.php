<?php

namespace TVS\Login\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
    
        /**
     * @ORM\ManyToMany(targetEntity="Privilege")
     * @ORM\JoinTable(name="route_privileges",
     * joinColumns={@ORM\JoinColumn(name="route_id", referencedColumnName="id", onDelete="CASCADE")},
     * inverseJoinColumns={@ORM\JoinColumn(name="privilege_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     * */
    private $privileges;
    
    public function __construct() {
        $this->privileges = new ArrayCollection();
    }
    
    function getPrivileges() {
        return $this->privileges->toArray();
    }

    function setPrivileges($privileges) {
        $this->privileges->add($privileges);
        return $this;
    }
    
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


    
}
