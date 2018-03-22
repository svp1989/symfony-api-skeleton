<?php

namespace App\Repository;

use App\Entity\InvalidToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvalidTokenRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvalidToken::class);
    }

    /**
     * @param int $timestamp
     */
    public function removeOldTokens(int $timestamp):void
    {
        $this->createQueryBuilder('token')
            ->delete()
            ->where('token.expiration < :timestamp')
            ->setParameter('timestamp', $timestamp)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $token
     * @param int $timestamp
     * @return boolean
     */
    public function hasActualToken(string $token, int $timestamp): bool
    {
        $hash = hash('sha256', $token, false);

        try {
            $this->createQueryBuilder('token')
                ->where('token.expiration >= :timestamp')
                ->setParameter('timestamp', $timestamp)
                ->andWhere('token.hash = :hash')
                ->setParameter('hash', $hash)
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
            return false;
        }

        return true;
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('p')
            ->where('p.something = :value')->setParameter('value', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
