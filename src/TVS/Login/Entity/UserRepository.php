<?php

namespace TVS\Login\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository {
    public function fatchPairs() {
        $entities = $this->findAll();
        $array = array();
        foreach ($entities as $entity) {
            $array[$entity->getId()] = $entity->getUsername();
        }
        return $array;
    }
    public function findByEmailAndPassword($email, $password) {
        $user = $this->findOneByEmail($email);

        if ($user) {
            if ($user->getPassword() == $user->encryptedPassword($password)) {
                return $user;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    public function findById($id) {
        $user = $this->findOneById($id);
        if ($user) {
            return $user;
        }
        return false;
    }
    
    public function findPagination($firstResult,$maxResults) {
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
    

}
