<?php
namespace Ibw\JobeetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ibw\JobeetBundle\Entity\Category;

class CategoryController extends Controller 
{
	public function showAction($id, $slug, $page)
	{
		$em = $this->getDoctrine()->getManager();

		$total_jobs = $em->getRepository('IbwJobeetBundle:Job')->getJobsCount($id);
		$per_page = $this->container->getParameter('max_jobs_on_category');
		$current_page = $page; 

		$paginator = $this->get('paginator');
		$paginator->init($total_jobs, $per_page, $current_page);


        $format = $this->getRequest()->getRequestFormat();

        $category = $em->getRepository('IbwJobeetBundle:Category')->findOneWithActiveJobs($slug, $paginator->per_page, $paginator->offset);

        if(!$category)
		{
			throw $this->createNotFoundException('Category not found !');
		}

        $latestJob = $em->getRepository('IbwJobeetBundle:Job')->getLatestJob($category->getId());

        if($latestJob) {
            $lastUpdated = $latestJob->getCreatedAt()->format(DATE_ATOM);
        } else {
            $lastUpdated = new \DateTime();
            $lastUpdated = $lastUpdated->format(DATE_ATOM);
        }

		return $this->render('IbwJobeetBundle:Category:show.'.$format.'.twig', array(
                    'category' => $category,
                    'paginator' => $paginator,
                    'lastUpdated' => $lastUpdated,
                    'feedId' => sha1($this->get('router')->generate("ibw_category_show", array("id" => $category->getId(), "slug" => $category->getSlug(), "page" => $page, "_format" => "atom")))
                ));
	}
}
?>