<?php 
namespace Ibw\JobeetBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ORM\NoResultException;

class JobRepository extends DocumentRepository
{
	public function findValidJobs($category_id = null)
	{
		$queryBuilder = $this->createQueryBuilder()
                        ->field('expires_at')->gt(date('Y-m-d H:i:s', time() - 86400 * 30))
                        ->field('activated')->equals(true)
                        ->sort('expires_at', 'DESC');

        if($category_id)
		{
            $queryBuilder->field('category', new \MongoCode($category_id));
		}
						
		return $queryBuilder->getQuery()->execute();
	}

	public function getActiveJob($id)
	{
		$queryBuilder = $this->createQueryBuilder()
                                ->field('expires_at')->gt(date('Y-m-d H:i:s', time() - 86400 * 30))
                                ->field('activated')->equals(true)
                                ->sort('expires_at', 'DESC')
                                ->getQuery();

		try
		{
			$job = $queryBuilder->getSingleResult();
		}
		catch(NoResultException $e)
		{
			$job = null;
		}

		return $job;
	}

	public function getJobsCount($category_id = null)
	{
		$qb = $this->createQueryBuilder()
                    ->field('expires_at')->gt(date('Y-m-d H:i:s', time() - 86400 * 30))
                    ->field('activated')->equals(true);

		if($category_id)
		{
            $qb->field('category', new \MongoCode($category_id));
		}

		$count = $qb->getQuery()->count();

		return $count;
	}

    public function cleanup($days)
    {
        $days = ($days > 0 && is_int($days)) ? $days : 0;
        $days = new \MongoCode($days);
        $query = $this->createQueryBuilder('j')
                        ->remove()
                        ->field('is_activated')->equals(null)
                        ->field('created_at')->lt((new \DateTime("-{$days} days"))->format('Y-m-d'))
                        ->getQuery();

        return $query->execute();
    }
}
?>