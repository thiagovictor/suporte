<?php

namespace TVS\Login\Entity;

use Doctrine\ORM\EntityRepository;

class MenuRepository extends EntityRepository {

    public function findPagination($firstResult, $maxResults) {
        return $this->createQueryBuilder('c')
                        ->setFirstResult($firstResult)
                        ->setMaxResults($maxResults)
                        ->getQuery()
                        ->getResult();
    }

    public function getRows() {
        return $this->createQueryBuilder('c')
                        ->select('Count(c)')
                        ->getQuery()
                        ->getSingleScalarResult();
    }

    public function findById($id) {
        $menu = $this->findOneById($id);
        if ($menu) {
            return $menu;
        }
        return false;
    }
    
    public function fatchPairs() {
        $entities = $this->findAll();
        $array = array();
        foreach ($entities as $entity) {
            $array[$entity->getId()] = $entity->getLabel();
        }
        return $array;
    }

}
