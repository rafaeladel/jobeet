<?php
namespace Ibw\JobeetBundle\Tests\Unit\Entity;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\CreateSchemaDoctrineCommand;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\ArrayInput;

abstract class ModelTestCase extends WebTestCase
{
	protected $em;
	protected $application;

//    protected function setUp()
//    {
//        static::$kernel = static::createKernel();
//        static::$kernel->boot();
//
//        $this->application = new Application(static::$kernel);
//        $this->application->setAutoExit(false);
//
//        // drop the database
//        $command = new DropDatabaseDoctrineCommand();
//        $this->application->add($command);
//        $input = new ArrayInput(array(
//            'command' => 'doctrine:database:drop',
//            '--force' => true
//        ));
//        $command->run($input, new NullOutput());
//
//        // we have to close the connection after dropping the database so we don't get "No database selected" error
//        $connection = $this->application->getKernel()->getContainer()->get('doctrine')->getConnection();
//        if ($connection->isConnected()) {
//            $connection->close();
//        }
//
//        // create the database
//        $command = new CreateDatabaseDoctrineCommand();
//        $this->application->add($command);
//        $input = new ArrayInput(array(
//            'command' => 'doctrine:database:create',
//        ));
//        $command->run($input, new NullOutput());
//
//        // create schema
//        $command = new CreateSchemaDoctrineCommand();
//        $this->application->add($command);
//        $input = new ArrayInput(array(
//            'command' => 'doctrine:schema:create',
//        ));
//        $command->run($input, new NullOutput());
//
//        // get the Entity Manager
//        $this->em = static::$kernel->getContainer()
//            ->get('doctrine')
//            ->getManager();
//
//        // load fixtures
//        $client = static::createClient();
//        $loader = new \Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader($client->getContainer());
//        $loader->loadFromDirectory(static::$kernel->locateResource('@IbwJobeetBundle/DataFixtures/ORM'));
//        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($this->em);
//        $executor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor($this->em, $purger);
//        $executor->execute($loader->getFixtures());
//    }

    public function setUp()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $this->em = $container->get('doctrine')->getManager();

        $schemaTool = new SchemaTool($this->em);
        $mdf = $this->em->getMetadataFactory();
        $classes = $mdf->getAllMetadata();

        $schemaTool->dropDatabase();
        $schemaTool->createSchema($classes);

        $loader = new \Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader($client->getContainer());
        $loader->loadFromDirectory(static::$kernel->locateResource('@IbwJobeetBundle/DataFixtures/ORM'));
        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($this->em);
        $executor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures());
    }

	protected function tearDown()
	{
		parent::tearDown();
	}
}
?>