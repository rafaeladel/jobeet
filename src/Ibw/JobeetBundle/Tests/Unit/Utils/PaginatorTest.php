<?php
namespace Ibw\JobeetBundle\Tests\Utils;

use Ibw\JobeetBundle\Utils\Paginator; 

class PaginatorTest extends \PHPUnit_Framework_TestCase 
{
	public function testPropertiesSet()
	{
		$paginator = new Paginator();
		$this->assertFalse(isset($paginator->pages_count));
		$this->assertFalse(isset($paginator->offset));

		$paginator->init(30, 5, 1);
		$this->assertTrue(isset($paginator->pages_count));
		$this->assertTrue(isset($paginator->offset));
	}

	public function testCorrectResults()
	{
		$paginator = new Paginator();

		$paginator->init(30, 5, 1);
		$this->assertEquals(6, $paginator->pages_count);
		$this->assertEquals(0, $paginator->offset);

		$paginator->init(30, 5, 2);
		$this->assertEquals(6, $paginator->pages_count);
		$this->assertEquals(5, $paginator->offset);

		$paginator->init(30, 5, 1);
		$this->assertFalse($paginator->hasPrev());

		$paginator->init(30, 5, 6);
		$this->assertFalse($paginator->hasNext());


		for($i = 8; $i <= 100; $i++)
		{
			$paginator->init(70, 9, $i);
			$this->assertEquals(8, $paginator->pages_count);
			$this->assertEquals(63, $paginator->offset);
		}

	}
}
?>