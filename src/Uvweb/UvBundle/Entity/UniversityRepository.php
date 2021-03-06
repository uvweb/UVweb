<?php

namespace Uvweb\UvBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UniversityRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UniversityRepository extends EntityRepository
{
  public function getWithMinimalInfo() {
    /*
    Returns an array of the different universities with the associated
    number of reviews and average grade 
    */
    $qb = $this->createQueryBuilder('u');
    $qb->where('u.approved = 1');
    $qb->leftJoin('u.classes', 'c', 'WITH', 'c.approved = 1')
      ->leftJoin('c.comments', 'm', 'WITH', 'm.moderated = 1')
      ->select('u')
      ->addSelect('COALESCE(COUNT(DISTINCT m)) as num_comments')
      ->addSelect('COALESCE(COUNT(DISTINCT c)) as num_classes')
      ->addSelect('ROUND(AVG(m.globalRate), 2) as avg_rate')
      ->groupBy('u.id');
    
    $universities = $qb->getQuery()->getResult();

    $sortedUniversities = array();
    foreach($universities as $university) {
      $sortedUniversities[$university[0]->getLocationCountry()][] = $university;
    }

    ksort($sortedUniversities);
    return $sortedUniversities;
  }

  public function getToBeApproved($withoutAddedBy = false)
  {
    $qb = $this->createQueryBuilder('u');
    $qb->where('u.approved = 0');
    if (!$withoutAddedBy)
    {
      $qb->andWhere('u.addedBy is not null');
    }
    $qb->orderBy('u.id', 'DESC');

    return $qb->getQuery()->getResult();
  }

  public function getByRate() {

  }
}
