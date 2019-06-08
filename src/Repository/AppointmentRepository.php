<?php

namespace App\Repository;

use App\Entity\Appointment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Appointment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appointment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appointment[]    findAll()
 * @method Appointment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * AppointmentRepository constructor.
     * @param RegistryInterface $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        RegistryInterface $registry,
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->queryBuilder = $entityManager->createQueryBuilder();

        parent::__construct($registry, Appointment::class);
    }

    /**
     * @param Appointment $appointment
     */
    public function save(Appointment $appointment)
    {
        $this->entityManager->persist($appointment);
        $this->entityManager->flush();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @return Appointment[] Returns an array of Appointment objects
     */
    public function findBetweenDays($dateFrom, $dateTo)
    {
        return $this->createQueryBuilder('a')
            ->andWhere($this->queryBuilder->expr()->between('a.datetime', ':date_from', ':date_to'))
            ->setParameter('date_from', $dateFrom, Type::DATETIME)
            ->setParameter('date_to', $dateTo, Type::DATETIME)
            ->orderBy('a.datetime', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    /**
     * @param $dateTime
     * @return Appointment|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByDateTime($dateTime): ?Appointment
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.datetime = :val')
            ->setParameter('val', $dateTime)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
