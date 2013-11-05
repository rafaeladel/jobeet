<?php
namespace Ibw\JobeetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ibw\JobeetBundle\Entity\Category;

class CategoryController extends Controller 
{
	public function showAction($id, $slug, $page)
	{
		$em = $this->get('doctrine_mongodb')->getManager();

		$total_jobs = $em->getRepository('IbwJobeetBundle:Job')->getJobsCount($id);
		$per_page = $this->container->getParameter('max_jobs_on_category');
		$current_page = $page; 

		$paginator = $this->get('paginator');
		$paginator->init($total_jobs, $per_page, $current_page);
		

		$category = $em->getRepository('IbwJobeetBundle:Category')->findOneWithActiveJobs($slug, $paginator->per_page, $paginator->offset);

		if(!$category)
		{
			throw $this->createNotFoundException('Category not found !');
		}

		return $this->render('IbwJobeetBundle:Category:show.html.twig', array('category' => $category, 'paginator' => $paginator));
	}
}
?>