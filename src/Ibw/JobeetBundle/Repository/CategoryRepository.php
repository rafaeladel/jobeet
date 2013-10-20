<?php

namespace Ibw\JobeetBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
	public function getWithAllJobs($limit = null)
	{
		$qb = $this->createQueryBuilder('c')
					->select('c, j')
					->innerJoin('c.jobs', 'j')
					->addOrderBy('j.expires_at', 'DESC');

		if($limit)
		{
			$qb->setMaxResults($limit);
		}

		return $qb->getQuery()->getResult();
	}

	public function getWithActiveJobs($limit = null)
	{
		$qb = $this->createQueryBuilder('c')
					->select('c, j')
					->innerJoin('c.jobs', 'j')
					->where('j.expires_at > :date')
					->setParameter('date', date('Y-m-d H:i:s', time()))
                    ->andWhere('j.is_activated = :activated ')
                    ->setParameter('activated', 1)
					->addOrderBy('j.expires_at', 'DESC');

		if($limit)
		{
			$qb->setMaxResults($limit);
		}
		return $qb->getQuery()->getResult();
	}

	public function findOneWithActiveJobs($slug, $max = null, $offset = null)
	{
		$qb = $this->createQueryBuilder('c')
					->select('c, j')
					->where('c.slug = :slug')
					->setParameter('slug', $slug)
					->innerJoin('c.jobs', 'j')
					->andWhere('j.expires_at > :date')
					->setParameter('date', date('Y-m-d H:i:s', time()))
                    ->andWhere('j.is_activated = :activated ')
                    ->setParameter('activated', 1)
					->addOrderBy('j.expires_at', 'DESC');

		if($max)
		{
			$qb->setMaxResults($max);
		}

		if($offset)
		{
			$qb->setFirstResult($offset);
		}

		try
		{
			$category = $qb->getQuery()->getSingleResult();
		}
		catch (Doctrine\ORM\NoResultException $e) 
		{
			$category = null;
		}

		return $category;
	}
}
