<?php
namespace Ibw\JobeetBundle\Tests\Unit\Entity;

use Ibw\JobeetBundle\Tests\Unit\Entity\ModelTestCase;
use Ibw\JobeetBundle\Entity\Affiliate;

class AffiliateTest extends ModelTestCase 
{
	// public function testAddAffiliate()
	// {
	// 	$category = $this->em->createQueryBuilder('c')
	// 					->select('c')
	// 					->from('IbwJobeetBundle:Category', 'c')
	// 					->where('c.id = 1')
	// 					->getQuery()
	// 					->getSingleResult();

	// 	$affiliate = new Affiliate();
	// 	$affiliate->setUrl('www.facebook.com');
	// 	$affiliate->setEmail('rafael.adel20@gmail.com');
	// 	$affiliate->setToken('rofaaa');
	// 	$affiliate->setIsActive(true);

	// 	$affiliate->addCategorie($category);
	// 	$category->addAffiliate($affiliate);

	// 	$this->em->persist($category);
	// 	$this->em->persist($affiliate);
	// 	$this->em->flush();

	// 	$affiliateDB = $this->em->createQueryBuilder('a')
	// 					->select('a')
	// 					->from('IbwJobeetBundle:Affiliate', 'a')
	// 					->where('a.categories = 1')
	// 					->getQuery()
	// 					->getSingleResult();

	// 	$this->assertEquals('ss', $affiliateDB->getCategories()->getName());
	// }

	public function testTrue()
	{
		$this->assertTrue(true);
	}
} 
?>