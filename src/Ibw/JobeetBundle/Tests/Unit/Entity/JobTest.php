<?php
namespace Ibw\JobeetBundle\Tests\Unit\Entity;

use Ibw\JobeetBundle\Utils\Jobeet;
use Ibw\JobeetBundle\Tests\Unit\Entity\ModelTestCase;

class JobeTest extends ModelTestCase
{
	public function testFetchAll()
	{
		$qb = $this->em->createQueryBuilder('j')
						->select('count(j.id)')
						->from("IbwJobeetBundle:Job", "j")
						->getQuery();
						
		$count = $qb->getSingleScalarResult();
		$this->assertEquals(34, $count);
	}

	public function testGetCompanySlug()
	{
		$job = $this->em->createQueryBuilder('j')
						->select('j')
						->from('IbwJobeetBundle:Job', 'j')
						->setMaxResults(1)
						->getQuery()
						->getSingleResult();
		$this->assertEquals($job->getCompanySlug(), Jobeet::slugify($job->getCompany()));

	}

	public function testGetPositionSlug()
	{
		$job = $this->em->createQueryBuilder('j')
						->select('j')
						->from('IbwJobeetBundle:Job', 'j')
						->setMaxResults(1)
						->getQuery()
						->getSingleResult();
		$this->assertEquals($job->getPositionSlug(), Jobeet::slugify($job->getPosition()));

	}

	public function testGetLocationSlug()
	{
		$job = $this->em->createQueryBuilder('j')
						->select('j')
						->from('IbwJobeetBundle:Job', 'j')
						->setMaxResults(1)
						->getQuery()
						->getSingleResult();
		$this->assertEquals($job->getLocationSlug(), Jobeet::slugify($job->getLocation()));
	}

	public function testSetCategory()
	{
		$job = $this->em->createQueryBuilder('j')
						->select('j')
						->from('IbwJobeetBundle:Job', 'j')
						->setMaxResults(1)
						->getQuery()
						->getSingleResult();

		$category = $this->em->createQueryBuilder('c')
						->select('c')
						->from('IbwJobeetBundle:Category', 'c')
						->setMaxResults(1)
						->getQuery()
						->getSingleResult(); 

		$job->setCategory($category);
		$this->em->persist($job);
		$this->em->flush();
		$this->assertEquals($category->getName(), $job->getCategory()->getName());

	}
	
}
?>