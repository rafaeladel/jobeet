<?php
namespace Ibw\JobeetBundle\Twig\Extensions;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

class JobeetExtensions extends \Twig_Extension
{
	public function __construct(ContainerInterface $container, RegistryInterface $doctrine)
	{
		$this->container = $container;
		$this->doctrine = $doctrine;

	}

	public function getFunctions()
	{
		return array(
			new \Twig_SimpleFunction('RemainingJobs', array($this, 'get_remaining_jobs')),
		);
	}

	public function get_remaining_jobs($category_id = null)
	{
		$repo = $this->doctrine->getRepository('IbwJobeetBundle:Job');
		$remainingJobsCount = $repo->getJobsCount($category_id) - $this->container->getParameter('max_jobs_on_homepage');
		return $remainingJobsCount > 0 ? $remainingJobsCount : null ;
	}

	public function getName()
	{
		return 'JobeetExtension';
	}
}
?>