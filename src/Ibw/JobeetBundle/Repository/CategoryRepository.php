<?php

namespace Ibw\JobeetBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ORM\NoResultException;

class CategoryRepository extends DocumentRepository
{
	public function getWithAllJobs($limit = null)
	{
		$qb = $this->createQueryBuilder()
					->field('jobs')->prime(true)
                    ->sort('jobs.expires_at', 'DESC');

		if($limit)
		{
			$qb->limit($limit);
		}

		return $qb->getQuery()->execute();
	}

	public function getWithActiveJobs($limit = null)
	{
		$qb = $this->createQueryBuilder()
                    ->field('jobs')->prime(true)
                    ->field('jobs.expires_at')->gt(date('Y-m-d H:i:s', time()))
                    ->field('jobs.is_activated')->equals(true)
					->sort('jobs.expires_at', 'DESC');

		if($limit)
		{
			$qb->limit($limit);
		}
		return $qb->getQuery()->execute();
	}

	public function findOneWithActiveJobs($slug, $max = null, $offset = null)
	{
		$qb = $this->createQueryBuilder()
                    ->field('jobs')->prime(true)
					->field('slug')->equals(new \MongoCode($slug))
                    ->field('jobs.expires_at')->gt(date('Y-m-d H:i:s', time()))
                    ->field('jobs.is_activated')->equals(true)
                    ->sort('jobs.expires_at', 'DESC');

		if($max)
		{
			$qb->limit($max);
		}

		if($offset)
		{
			$qb->skip($offset);
		}

		try
		{
			$category = $qb->getQuery()->getSingleResult();
		}
		catch (NoResultException $e)
		{
			$category = null;
		}

		return $category;
	}
}
