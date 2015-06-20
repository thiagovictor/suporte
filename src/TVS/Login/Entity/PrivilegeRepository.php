<?php

namespace TVS\Login\Entity;

use Doctrine\ORM\EntityRepository;

class PrivilegeRepository extends EntityRepository {

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

//    public function validation(User $user, $rota) {
//        $qb = $this->createQueryBuilder();
//        return $qb->select('p')
//                        ->from('Privilege', 'p')
//                        ->where('p.user.id = :user_id and (p.route.id = :route.id or p.route.route = "*")')
//                        ->setParameter('identifier', 100);
//    }

}
