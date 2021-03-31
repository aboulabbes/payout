<?php


namespace App\Repository;
use App\Entity\Payout;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class PayoutRepository
 * @package App\Repository
 */
class PayoutRepository extends ServiceEntityRepository
{

    /**
     * PayoutRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry,Payout::class);
    }
}