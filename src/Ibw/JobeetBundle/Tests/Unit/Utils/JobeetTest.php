<?php
namespace Ibw\JobeetBundle\Tests\Unit\Utils;

use Ibw\JobeetBundle\Utils\Jobeet;

class JobeetTest extends \PHPUnit_Framework_TestCase
{
	public function testSlugify()
	{	
		$this->assertEquals("my-name-is-earl", Jobeet::slugify("My Name is Earl"));
		$this->assertEquals("my-name-is-earl", Jobeet::slugify("-My Name is Earl-"));
		$this->assertEquals("n-a", Jobeet::slugify(""));
		$this->assertEquals("n-a", Jobeet::slugify(" - "));
		$this->assertEquals('developpeur-web', Jobeet::slugify('Développeur Web'));
	}
}

?>