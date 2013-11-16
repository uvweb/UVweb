<?php

namespace Uvweb\UvBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UvRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UvRepository extends EntityRepository
{
	public function getUvNamesLike($uvLike, $limit = 0)
	{
        $qb = $this->createQueryBuilder('u');
        $qb->select('u.name');
        $qb->where($qb->expr()->like('u.name', $qb->expr()->literal('%' . $uvLike . '%')));
        $qb->orderBy('u.name');

        if($limit)
            $qb->setMaxResults($limit);

        return $qb->getQuery()->getScalarResult();
	}
}
