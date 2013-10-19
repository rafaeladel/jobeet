<?php
namespace Ibw\JobeetBundle\Tests\Unit\Repository;

use Ibw\JobeetBundle\Tests\Unit\Entity\ModelTestCase;

class JobRepositoryTest extends ModelTestCase 
{
	public function testFindValidJobs()
	{
		$repo = $this->em->getRepository('IbwJobeetBundle:Job');

		$jobs = $repo->findValidJobs();
		$this->assertEquals(33, count($jobs));

		$jobs = $repo->findValidJobs(1);
		$this->assertEquals(1, count($jobs));

		$jobs = $repo->findValidJobs(2);
		$this->assertEquals(32, count($jobs));
	}

	public function testFindActiveJob()
	{
		$repo = $this->em->getRepository('IbwJobeetBundle:Job');
		
		$job = $repo->getActiveJob(1);
		$this->assertInstanceOf('Ibw\JobeetBundle\Entity\Job', $job);

		$job = $repo->getActiveJob(32);
		$this->assertNull($job);
	}

	public function testGetJobsCount()
	{
		$repo = $this->em->getRepository('IbwJobeetBundle:Job');
		
		$count = $repo->getJobsCount();
		$this->assertEquals(33, $count);

		$count = $repo->getJobsCount(1);
		$this->assertEquals(1, $count);

		$count = $repo->getJobsCount(2);
		$this->assertEquals(32, $count);

		$count = $repo->getJobsCount(22);
		$this->assertEquals(0, $count);
	}

}
?>