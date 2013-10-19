<?php
namespace Ibw\JobeetBundle\Controller;

use Ibw\JobeetBundle\Entity\Job;
use Ibw\JobeetBundle\Forms\JobType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class JobController extends Controller 
{
	public function indexAction()
	{
		$repo = $this->getDoctrine()->getManager()->getRepository("IbwJobeetBundle:Category");
		$categories = $repo->getWithActiveJobs($this->container->getParameter('max_jobs_on_homepage'));

		return $this->render("IbwJobeetBundle:Job:index.html.twig", array("categories" => $categories));
	}

	public function showAction($id, $company, $position, $location)
	{
		$repo = $this->getDoctrine()->getManager()->getRepository("IbwJobeetBundle:Job");
		$entity = $repo->find($id);

		if(!$entity)
		{
			throw $this->createNotFoundException("Job not found!");
		}

		return $this->render("IbwJobeetBundle:Job:show.html.twig", array("entity" => $entity));
	}


	public function newAction()
	{
		$entity = new Job();
		$form = $this->createForm(new JobType(), $entity);

		return $this->render('IbwJobeetBundle:Job:new.html.twig', array(
	        'entity' => $entity,
	        'form'   => $form->createView(),
	     ));
	}

	public function createAction(Request $request)
	{
		$entity = new Job();
		$form = $this->createForm(new JobType(), $entity);
		$form->handleRequest($request);

		if($form->isValid())
		{
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this->redirect($this->generateUrl('ibw_job_show', array(
					'company' 	=> $entity->getCompanySlug(),
					'location' 	=> $entity->getLocationSlug(),
					'position'	=> $entity->getPositionSlug(),
					'id'		=> $entity->getId(),
				)));
		} else {
			return $this->render('IbwJobeetBundle:Job:new.html.twig', array(
					'form' => $form->createView(),
				));
		}
	}

	public function editAction($token)
	{	
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('IbwJobeetBundle:Job')->findOneByToken($token);
		if(!$entity)
		{
			throw $this->createNotFoundException('Job not found !');
		}

		$form = $this->createForm(new JobType(), $entity);

		return $this->render('IbwJobeetBundle:Job:edit.html.twig', array(
	        'entity' => $entity,
	        'edit_form'   => $form->createView(),
	     ));
	}

	public function updateAction(Request $request, $token)
	{	
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('IbwJobeetBundle:Job')->findOneByToken($token);
		if(!$entity)
		{
			throw $this->createNotFoundException('Job not found !');
		}

		$form = $this->createForm(new JobType(), $entity);

		$form->handleRequest($request);

		if($form->isValid())
		{
			$em->persist($entity);
			$em->flush();
			return $this->redirect($this->generateUrl('ibw_job_edit', array(
					'token'	=>	$entity->getToken(),
				)));
		}

		return $this->render('IbwJobeetBundle:Job:edit.html.twig', array(
	        'entity' => $entity,
	        'edit_form'   => $form->createView(),
	     ));
	}
}
?>