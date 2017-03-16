<?php
namespace app\UserModule\Repository;

use app\UserModule\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 * @package app\UserModule\Repository
 */
class UserRepository extends EntityRepository
{
    /**
     * @param $login
     * @param $password
     * @return User
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function authenticate($login, $password){
        $qb = $this->createQueryBuilder('u');
        $qb->where('u.login =  :login')
            ->andWhere('u.password = :password')
            ->setParameter('login', $login)
            ->setParameter('password', $password);

        return $qb->getQuery()->getOneOrNullResult();
    }
}