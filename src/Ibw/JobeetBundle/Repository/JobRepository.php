<?php 
namespace Ibw\JobeetBundle\Repository;

use Doctrine\ORM\EntityRepository;

class JobRepository extends EntityRepository
{
	public function findValidJobs($category_id = null)
	{
		$queryBuilder = $this->createQueryBuilder('j')
						->select('j')
						->where('j.expires_at > :date')
						->setParameter('date', date('Y-m-d H:i:s', time() - 86400 * 30))
						->addOrderBy('j.expires_at', 'DESC');
		if($category_id)
		{
			$queryBuilder->andWhere('j.category = :c_id')
							->setParameter('c_id', $category_id);
		}
						
		return $queryBuilder->getQuery()->getResult();
	}

	public function getActiveJob($id)
	{
		$queryBuilder = $this->createQueryBuilder('j')
						->select('j')
						->where('j.expires_at > :date')
						->andWhere('j.id = :id')
						->setParameters(array('date' => date('Y-m-d H:i:s', time() - 86400 * 30), 'id' => $id))
						->addOrderBy('j.expires_at', 'DESC')
						->getQuery();

		try
		{
			$job = $queryBuilder->getSingleResult();
		}
		catch(\Doctrine\ORM\NoResultException $e)
		{
			$job = null;
		}

		return $job;
	}

	public function getJobsCount($category_id = null)
	{
		$qb = $this->createQueryBuilder('j')
					->select('count(j.id)')
					->where('j.expires_at > :date')
					->setParameter('date', date('Y-m-d H:i:s', time()));

		if($category_id)
		{
			$qb->andWhere('j.category = :cat_id')
				->setParameter('cat_id' , $category_id);
		}

		$count = $qb->getQuery()->getSingleScalarResult();

		return $count;
	}
}
?>