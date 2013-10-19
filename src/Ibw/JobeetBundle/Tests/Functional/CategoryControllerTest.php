<?php
	namespace Ibw\JobeetBundle\Tests\Functional;

	use Ibw\JobeetBundle\Tests\Unit\Entity\ModelTestCase;

	class CategoryControllerTest extends ModelTestCase 
	{
		public function testShow()
		{
			$category = $this->em->getRepository('IbwJobeetBundle:Category')->findAll()[1];

			$client = static::createClient();
			$crawler = $client->request('GET', "/category/{$category->getId()}/{$category->getSlug()}/1");
			$this->assertEquals('Ibw\JobeetBundle\Controller\CategoryController::showAction', $client->getRequest()->attributes->get('_controller'));
			$this->assertEquals(200 , $client->getResponse()->getStatusCode());
			$this->assertEquals(33, count($category->getJobs()));
		}
	}
?>