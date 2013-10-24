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

		public function testNewJobGet()
		{
			$client = static::createClient();
			$crawler = $client->request('GET', '/job/new');
			$this->assertEquals(200, $client->getResponse()->getStatusCode());
            $this->assertEquals('Ibw\JobeetBundle\Controller\JobController::newAction', $client->getRequest()->attributes->get('_controller'));
		}

        public function testNewJobPost()
        {
            $client = $this->createJob();
            $this->assertEquals('Ibw\JobeetBundle\Controller\JobController::previewAction', $client->getRequest()->attributes->get('_controller'));

            $job_count = $this->em->createQueryBuilder('j')
                            ->select('count(j.id)')
                            ->from('IbwJobeetBundle:Job', 'j')
                            ->where('j.location = :location')
                            ->setParameter('location', 'Atlanta, USA')
                            ->andWhere('j.is_activated is null')
                            ->andWhere('j.is_public = 0')
                            ->getQuery()->getSingleScalarResult();
            $this->assertTrue($job_count > 0);
        }

        public function testNewJobPostError()
        {
            $client = static::createClient();
            $crawler = $client->request('GET', '/job/new');
            $form = $crawler->selectButton('Preview your job')->form(array(
                'job[company]'      => 'Sensio Labs',
                'job[position]'     => 'Developer',
                'job[location]'     => 'Atlanta, USA',
                'job[email]'        => 'not.an.email',
            ));
            $crawler = $client->submit($form);
            $this->assertEquals('Ibw\JobeetBundle\Controller\JobController::createAction', $client->getRequest()->attributes->get('_controller'));
            $this->assertFalse($client->getResponse()->isRedirection());
            $this->assertEquals($crawler->filter(".error_list li")->count(), 3);
        }

        public function testEditActivatedJobGet()
        {
            $token = $this->em->createQueryBuilder('j')
                                ->select('j')
                                ->from('IbwJobeetBundle:Job', 'j')
                                ->where("j.is_activated = 1")
                                ->setMaxResults(1)
                                ->getQuery()->getSingleResult()->getToken();
            $client = static::createClient();
            $crawler = $client->request('GET', sprintf("/%s/edit", $token));
            $this->assertEquals('Ibw\JobeetBundle\Controller\JobController::editAction', $client->getRequest()->attributes->get('_controller'));
            $this->assertEquals(404, $client->getResponse()->getStatusCode());
        }

        public function testEditJobGet()
        {
            $job = $this->em->createQueryBuilder('j')
                                ->select('j')
                                ->from('IbwJobeetBundle:Job', 'j')
                                ->setMaxResults(1)
                                ->getQuery()->getSingleResult();
            $job->setIsActivated(false);
            $this->em->flush();
            $client = static::createClient();
            $crawler = $client->request('GET', sprintf("/%s/edit", $job->getToken()));
            $this->assertEquals('Ibw\JobeetBundle\Controller\JobController::editAction', $client->getRequest()->attributes->get('_controller'));
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
        }

        public function testPublishForm()
        {
            $client = $this->createJob();
            $crawler = $client->getCrawler();

            $this->assertEquals('Ibw\JobeetBundle\Controller\JobController::previewAction', $client->getRequest()->attributes->get('_controller'));
            $this->assertEquals(1, $crawler->filter('div#job_actions')->count());
            $form = $crawler->selectButton('Publish')->form();

            $job_count = $this->em->createQueryBuilder('j')
                ->select('count(j.id)')
                ->from('IbwJobeetBundle:Job', 'j')
                ->where('j.location = :location')
                ->setParameter('location', 'Atlanta, USA')
                ->andWhere('j.is_activated = 1')
                ->getQuery()->getSingleScalarResult();

            $this->assertEquals(0, $job_count);

            $client->submit($form);

            $job_count = $this->em->createQueryBuilder('j')
                ->select('count(j.id)')
                ->from('IbwJobeetBundle:Job', 'j')
                ->where('j.location = :location')
                ->setParameter('location', 'Atlanta, USA')
                ->andWhere('j.is_activated = 1')
                ->getQuery()->getSingleScalarResult();

            $this->assertEquals(1, $job_count);
        }

        public function testDeleteForm()
        {
            $client = $this->createJob();
            $crawler = $client->getCrawler();

            $this->assertEquals('Ibw\JobeetBundle\Controller\JobController::previewAction', $client->getRequest()->attributes->get('_controller'));
            $this->assertEquals(1, $crawler->filter('div#job_actions')->count());
            $form = $crawler->selectButton('Delete')->form();

            $job_count = $this->em->createQueryBuilder('j')
                ->select('count(j.id)')
                ->from('IbwJobeetBundle:Job', 'j')
                ->where('j.location = :location')
                ->setParameter('location', 'Atlanta, USA')
                ->andWhere('j.is_activated is Null')
                ->getQuery()->getSingleScalarResult();

            $this->assertEquals(1, $job_count);

            $client->submit($form);

            $job_count = $this->em->createQueryBuilder('j')
                ->select('count(j.id)')
                ->from('IbwJobeetBundle:Job', 'j')
                ->where('j.location = :location')
                ->setParameter('location', 'Atlanta, USA')
                ->andWhere('j.is_activated is Null')
                ->getQuery()->getSingleScalarResult();

            $this->assertEquals(0, $job_count);
        }

        public function testExtendJob()
        {
            $client = $this->createJob(array('job[position]' => 'FOO1'), true);
            $crawler = $client->getCrawler();
            $this->assertEquals(0, $crawler->filter('input[value=Extend]')->count());

            $job = $this->em->getRepository('IbwJobeetBundle:Job')->findOneByPosition("FOO1");
            $job->setExpiresAt(new \DateTime());
            $this->em->persist($job);
            $this->em->flush();

            $crawler = $client->reload();
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
            $this->assertEquals('Ibw\JobeetBundle\Controller\JobController::previewAction', $client->getRequest()->attributes->get('_controller'));
            $this->assertEquals(1, $crawler->filter('input[value=Extend]')->count());

            $form = $crawler->selectButton('Extend')->form();
            $client->submit($form);
            $this->assertEquals('Ibw\JobeetBundle\Controller\JobController::extendAction', $client->getRequest()->attributes->get('_controller'));

            $edited_job = $this->em->getRepository('IbwJobeetBundle:Job')->findOneByPosition("FOO1");
//            $this->assertEquals((new \DateTime('+1 month'))->format('y/m/d'), $edited_job->getExpiresAt()->format('y/m/d'));
        }

        public function createJob(array $values = array(), $publish = false)
        {
            $client = static::createClient();
            $crawler = $client->request('GET', '/job/new');
            $default_input = array(
                'job[company]'      => 'Rafael Co.',
                'job[url]'          => 'http://www.sensio.com/',
                'job[file]'         => __DIR__.'/../../../../../web/bundles/ibwjobeet/images/sensio-labs.gif',
                'job[position]'     => 'Developer',
                'job[location]'     => 'Atlanta, USA',
                'job[description]'  => 'You will work with symfony to develop websites for our customers.',
                'job[how_to_apply]' => 'Send me an email',
                'job[email]'        => 'for.a.job@example.com',
                'job[is_public]'    => false,
            );

            $input = array_merge($default_input, $values);
            $form = $crawler->selectButton("Preview your job")->form($input);
            $client->submit($form);
            $client->followRedirect();

            if($publish)
            {
                $crawler = $client->getCrawler();
                $form = $crawler->selectButton('Publish')->form();
                $client->submit($form);
                $client->followRedirect();
            }
            return $client;
        }

        public function getError($response, $crawler)
        {
            if (!$response->isSuccessful()) {
                $block = $crawler->filter('div.text-exception > h1');
                if ($block->count()) {
                    $error = $block->text();
                    return $error;
                }
            }
        }
	}
?>