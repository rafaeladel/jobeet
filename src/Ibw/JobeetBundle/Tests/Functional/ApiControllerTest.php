<?php
namespace Ibw\JobeetBundle\Tests\Functional;

use Ibw\JobeetBundle\Tests\Unit\Entity\ModelTestCase;

class ApiControllerTest extends ModelTestCase
{
    public function testList()
    {
        $client = static::createClient();
        $crawler = $client->request("GET", "/api/sensio-labs/jobs.xml");
        $this->assertEquals("Ibw\JobeetBundle\Controller\ApiController::listAction", $client->getRequest()->attributes->get("_controller"));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(32 , $crawler->filter("description")->count());

        $crawler = $client->request("GET", "/api/sensio-labs23/jobs.xml");
        $this->assertEquals("Ibw\JobeetBundle\Controller\ApiController::listAction", $client->getRequest()->attributes->get("_controller"));
        $this->assertEquals(404, $client->getResponse()->getStatusCode());

        $crawler = $client->request("GET", "/api/sensio-labs/jobs.json");
        $this->assertEquals("Ibw\JobeetBundle\Controller\ApiController::listAction", $client->getRequest()->attributes->get("_controller"));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegExp('/"category"\:"Programming"/', $client->getResponse()->getContent());
    }
}
?>