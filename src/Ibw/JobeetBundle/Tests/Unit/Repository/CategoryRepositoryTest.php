<?php
namespace Ibw\JobeetBundle\Tests\Unit\Repository;

use Ibw\JobeetBundle\Tests\Unit\Entity\ModelTestCase;

class CategoryRepositoryTest extends ModelTestCase 
{
	public function testGetWithAllJobs()
	{
		$repo = $this->em->getRepository('IbwJobeetBundle:Category');

		$categories = $repo->getWithAllJobs();
		$this->assertEquals(2, count($categories));
		$this->assertInstanceOf('Ibw\JobeetBundle\Entity\Category', $categories[0]);
		$this->assertEquals(1, $categories[0]->getId());
		$this->assertEquals(2, $categories[1]->getId());
		$this->assertEquals(1, count($categories[0]->getJobs()));
		$this->assertEquals(33, count($categories[1]->getJobs()));

		$categories = $repo->getWithAllJobs(1);
		$this->assertEquals(1, count($categories));
		$this->assertInstanceOf('Ibw\JobeetBundle\Entity\Category', $categories[0]);
		$this->assertEquals(1, $categories[0]->getId());
	}

	public function testGetWithActiveJobs()
	{
		$repo = $this->em->getRepository('IbwJobeetBundle:Category');

		$categories = $repo->getWithActiveJobs();
		$this->assertEquals(2, count($categories));
		$this->assertInstanceOf('Ibw\JobeetBundle\Entity\Category', $categories[0]);
		$this->assertEquals(2, $categories[0]->getId());
		$this->assertEquals(1, $categories[1]->getId());
		$this->assertEquals(32, count($categories[0]->getJobs()));
		$this->assertEquals(1, count($categories[1]->getJobs()));

		$categories = $repo->getWithActiveJobs(1);
		$this->assertEquals(1, count($categories));
		$this->assertInstanceOf('Ibw\JobeetBundle\Entity\Category', $categories[0]);
		$this->assertEquals(2, $categories[0]->getId());
	}

	public function testFindOneWithActiveJobs()
	{
		$repo = $this->em->getRepository('IbwJobeetBundle:Category');	

		$category = $repo->findOneWithActiveJobs('programming');
		$this->assertInstanceOf('Ibw\JobeetBundle\Entity\Category', $category);
		$this->assertEquals(32, count($category->getJobs()));

		// $category = $repo->findOneWithActiveJobs('programming', 10);
		// $this->assertInstanceOf('Ibw\JobeetBundle\Entity\Category', $category);
		// $this->assertEquals(10, count($category->getJobs()));
	}
}