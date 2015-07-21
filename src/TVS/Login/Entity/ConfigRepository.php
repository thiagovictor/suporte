<?php

namespace TVS\Login\Entity;

use Doctrine\ORM\EntityRepository;

class ConfigRepository extends EntityRepository {

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
        $config = $this->findOneById($id);
        if ($config) {
            return $config;
        }
        return false;
    }
    
    public function fatchPairs() {
        $entities = $this->findAll();
        $array = array();
        foreach ($entities as $entity) {
            $array[$entity->getId()] = $entity->getNome();
        }
        return $array;
    }

}
