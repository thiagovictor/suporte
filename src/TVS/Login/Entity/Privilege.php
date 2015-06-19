<?php

namespace TVS\Login\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="TVS\Login\Entity\PrivilegeRepository")
 * @ORM\Table(name="privilege")
 */
class Privilege {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue 
     */
    private $id;
    
    /**
     * @ORM\Column(name="route", type="string", nullable=false) 
     */
    private $rota;

    /**
     * @ORM\Column(name="display", type="boolean", nullable=false) 
     */
    private $display;
    
    /**
     * @ORM\Column(name="new", type="boolean", nullable=false) 
     */
    private $new;
    
    /**
     * @ORM\Column(name="edit", type="boolean", nullable=false) 
     */
    private $edit;
    
    /**
     * @ORM\Column(name="delete", type="boolean", nullable=false) 
     */
    private $delete;
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $user;
    
    function getRota() {
        return $this->rota;
    }

    function setRota($rota) {
        $this->rota = $rota;
        return $this;
    }
    
    function getId() {
        return $this->id;
    }

    function getDisplay() {
        return $this->display;
    }

    function getNew() {
        return $this->new;
    }

    function getEdit() {
        return $this->edit;
    }

    function getDelete() {
        return $this->delete;
    }

    function setId($id) {
        $this->id = $id;
        return $this;
    }

    function setDisplay($display) {
        $this->display = $display;
        return $this;
    }

    function setNew($new) {
        $this->new = $new;
        return $this;
    }

    function setEdit($edit) {
        $this->edit = $edit;
        return $this;
    }

    function setDelete($delete) {
        $this->delete = $delete;
        return $this;
    }




}
