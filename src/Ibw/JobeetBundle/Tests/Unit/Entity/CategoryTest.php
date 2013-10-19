<?php
namespace Ibw\JobeetBundle\Tests\Unit\Entity;

use Ibw\JobeetBundle\Tests\Unit\Entity\ModelTestCase;
use Ibw\JobeetBundle\Entity\Job;

class CategoryTest extends ModelTestCase 
{
	public function testCategoryCount()
	{
		$qb = $this->em->createQueryBuilder('c')
						->select('count(c.id)')
						->from('IbwJobeetBundle:Category', 'c')
						->getQuery();
		$count = $qb->getSingleScalarResult();
		$this->assertEquals(4, $count);
	}

	public function testRelatedJobs()
	{
		$category = $this->em->createQueryBuilder('c')
						->select('c')
						->from('IbwJobeetBundle:Category', 'c')
						->where('c.id = 2')
						->getQuery()
						->getSingleResult();

		$jobFromQuery = $this->em->createQueryBuilder('j')
						->select('j')
						->from('IbwJobeetBundle:Job', 'j')
						->where('j.category = 2')
						->setMaxResults(1)
						->setFirstResult(1)
						->getQuery()
						->getSingleResult();

		$jobs = $category->getJobs();

		$this->assertInstanceOf('Doctrine\ORM\PersistentCollection', $jobs);

		//Second job
		$this->assertEquals($jobFromQuery->getCompany(), $jobs[1]->getCompany());
	}

	public function testChangeJobCategory()
	{
		$category = $this->em->createQueryBuilder('c')
						->select('c')
						->from('IbwJobeetBundle:Category', 'c')
						->where('c.id = 1')
						->getQuery()
						->getSingleResult();

		$jobFromQuery = $this->em->createQueryBuilder('j')
						->select('j')
						->from('IbwJobeetBundle:Job', 'j')
						->where('j.category = 2')
						->setMaxResults(1)
						->setFirstResult(1)
						->getQuery()
						->getSingleResult();

		$this->assertEquals(2, $jobFromQuery->getCategory()->getId());

		// $category->addJob($jobFromQuery);
		$jobFromQuery->setCategory($category);
		$this->em->persist($jobFromQuery);
		$this->em->flush();

		$this->assertEquals(1, $jobFromQuery->getCategory()->getId());
	}

	public function testAddJobToCategory()
	{
		$job = new Job();
         // $job->setCategory($em->merge($this->getReference('category-programming')));
         $job->setType('flexible-time');
         $job->setCompany('Sensio Labs');
         $job->setLogo('sensio-labs.gif');
         $job->setUrl('http://www.sensiolabs.com/');
         $job->setPosition('Web Developer');
         $job->setLocation('Paris, France');
         $job->setDescription('You\'ve already developed websites with symfony and you want to work with Open-Source technologies. You have a minimum of 3 years experience in web development with PHP or Java and you wish to participate to development of Web 2.0 sites using the best frameworks available.');
         $job->setHowToApply('Send your resume to fabien.potencier [at] sensio.com');
         $job->setIsPublic(true);
         $job->setIsActivated(true);
         $job->setToken('job');
         $job->setEmail('job@example.com');
         $job->setExpiresAt(new \DateTime('+30 days'));

         $category = $this->em->createQueryBuilder('c')
						->select('c')
						->from('IbwJobeetBundle:Category', 'c')
						->where('c.id = 1')
						->getQuery()
						->getSingleResult();

		$category->addJob($job);
		$this->em->persist($category);
		$job->setCategory($category);
		$this->em->persist($job);
		$this->em->flush();

		$jobFromQuery = $this->em->createQueryBuilder('j')
						->select('j')
						->from('IbwJobeetBundle:Job', 'j')
						->where('j.category = 1')
						->setMaxResults(1)
						->setFirstResult(1)
						->getQuery()
						->getSingleResult();


		$this->assertEquals('flexible-time', $jobFromQuery->getType());
	}
}
?>