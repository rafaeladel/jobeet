<?php
	namespace Ibw\JobeetBundle\Tests\Functional;

	use Ibw\JobeetBundle\Tests\Unit\Entity\ModelTestCase;

	class JobControllerTest extends ModelTestCase 
	{
		public function testIndex()
		{
			$client = static::createClient();
			$crawler = $client->request('GET', "/");
			$this->assertEquals('Ibw\JobeetBundle\Controller\JobController::indexAction', $client->getRequest()->attributes->get('_controller'));
			$this->assertEquals(200 , $client->getResponse()->getStatusCode());
			$this->assertEquals(0, $crawler->filter('.jobs td.position:contains("Expired")')->count());
		
		}

		public function testCategoryCount()
		{
			$client = static::createClient();
			$crawler = $client->request("GET", "/");
			$this->assertEquals(200 , $client->getResponse()->getStatusCode());
			$this->assertEquals(1, $crawler->filter('.category')->count());
		}

		public function testJobLink()
		{
			$category = $this->em->getRepository('IbwJobeetBundle:Category')->findOneWithActiveJobs('programming', 1);
			$job = $category->getJobs()[0];
			$client = static::createClient();
			$crawler = $client->request('GET', '/');
			$link = $crawler->selectLink('Web Developer')->first()->link();
			$crawler = $client->click($link);
			$this->assertEquals('Ibw\JobeetBundle\Controller\JobController::showAction', $client->getRequest()->attributes->get('_controller'));
			$this->assertEquals($job->getCompanySlug(), $client->getRequest()->attributes->get('company'));
			$this->assertEquals($job->getPositionSlug(), $client->getRequest()->attributes->get('position'));
			$this->assertEquals($job->getLocationSlug(), $client->getRequest()->attributes->get('location'));
			$this->assertEquals($job->getId(), $client->getRequest()->attributes->get('id'));
		}

		public function testNewJob()
		{
			$client = static::createClient();
			$crawler = $client->request('GET', '/job/new');
			$this->assertEquals(200, $client->getResponse()->getStatusCode());
		}
	}
?>