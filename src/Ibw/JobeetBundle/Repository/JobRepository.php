<?php 
namespace Ibw\JobeetBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\Tests\ORM\Query\SelectSqlGenerationTest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class JobRepository extends EntityRepository
{
	public function findValidJobs($category_id = null)
	{
		$queryBuilder = $this->createQueryBuilder('j')
						->select('j')
						->where('j.expires_at > :date')
						->setParameter('date', date('Y-m-d H:i:s', time() - 86400 * 30))
                        ->andWhere('j.is_activated = :activated ')
                        ->setParameter('activated', 1)
						->addOrderBy('j.expires_at', 'DESC');
		if($category_id)
		{
			$queryBuilder->andWhere('j.category = :c_id')
							->setParameter('c_id', $category_id);
		}
						
		return $queryBuilder->getQuery()->getResult();
	}

    public function getActiveJobs($category_id = null, $max = null, $offset = null, $affiliate_id = null)
    {
        $qb = $this->createQueryBuilder('j')
            ->where('j.expires_at > :date')
            ->setParameter('date', date('Y-m-d H:i:s', time()))
            ->andWhere('j.is_activated = :activated')
            ->setParameter('activated', 1)
            ->orderBy('j.expires_at', 'DESC');

        if($max) {
            $qb->setMaxResults($max);
        }

        if($offset) {
            $qb->setFirstResult($offset);
        }

        if($category_id) {
            $qb->andWhere('j.category = :category_id')
                ->setParameter('category_id', $category_id);
        }
        // j.category c, c.affiliate a
        if($affiliate_id) {
            $qb->leftJoin('j.category', 'c')
                ->leftJoin('c.affiliates', 'a')
                ->andWhere('a.id = :affiliate_id')
                ->setParameter('affiliate_id', $affiliate_id)
            ;
        }

        $query = $qb->getQuery();

        return $query->getResult();
    }
    
	public function getActiveJob($id)
	{
		$queryBuilder = $this->createQueryBuilder('j')
						->select('j')
						->where('j.expires_at > :date')
						->andWhere('j.id = :id')
						->setParameters(array('date' => date('Y-m-d H:i:s', time() - 86400 * 30), 'id' => $id))
                        ->andWhere('j.is_activated = :activated ')
                        ->setParameter('activated', 1)
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
					->setParameter('date', date('Y-m-d H:i:s', time()))
                    ->andWhere('j.is_activated = :activated ')
                    ->setParameter('activated', 1);

		if($category_id)
		{
			$qb->andWhere('j.category = :cat_id')
				->setParameter('cat_id' , $category_id);
		}

		$count = $qb->getQuery()->getSingleScalarResult();

		return $count;
	}

    public function getLatestJob($category_id = null)
    {
        $q = $this->createQueryBuilder("j")
                    ->select("j")
                    ->where("j.expires_at > :date")
                    ->setParameter("date", date("Y-m-d H:i:s", time()))
                    ->andWhere("j.is_activated = :activated")
                    ->setParameter("activated", 1)
                    ->orderBy("j.expires_at", "DESC")
                    ->setMaxResults(1);
        if($category_id)
        {
            $q->andWhere("j.category = :category_id")
                ->setParameter("category_id", $category_id);
        }

        try
        {
            $latestJob = $q->getQuery()->getSingleResult();
        }
        catch(NoResultException $e)
        {
            $latestJob = null;
        }

        return $latestJob;
    }

    public function cleanup($days)
    {
        $days = ($days > 0 && is_int($days)) ? $days : 0;
        $query = $this->createQueryBuilder('j')
                        ->delete()
                        ->where('j.is_activated IS NULl')
                        ->andWhere('j.created_at < :created_at')
                        ->setParameter('created_at', (new \DateTime("-{$days} days"))->format('Y-m-d'))
                        ->getQuery();

        return $query->execute();
    }
}
?>