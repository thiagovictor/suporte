<?php

namespace TVS\Login\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="TVS\Login\Entity\MenuRepository")
 * @ORM\Table(name="menu")
 */
class Menu {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue 
     */
    private $id;

    /**
     * @ORM\Column(name="menu", type="string", nullable=false) 
     */
    private $desc;

    /**
     * @ORM\Column(name="label", type="string", nullable=false) 
     */
    private $label;
    
    function getId() {
        return $this->id;
    }


    function getLabel() {
        return $this->label;
    }

    function setId($id) {
        $this->id = $id;
        return $this;
    }

    function setLabel($label) {
        $this->label = $label;
        return $this;
    }

    function getDesc() {
        return $this->desc;
    }

    function setDesc($desc) {
        $this->desc = $desc;
        return $this;
    }
    
    public function toArray() {
        return [
            'id' => $this->getId(),
            'desc' => $this->getDesc(),
            'label' => $this->getLabel()
        ];
    }
    
}
