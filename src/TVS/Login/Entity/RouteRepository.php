<?php

namespace TVS\Login\Entity;

use Doctrine\ORM\EntityRepository;

class RouteRepository extends EntityRepository {

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
        $route = $this->findOneById($id);
        if ($route) {
            return $route;
        }
        return false;
    }

}
