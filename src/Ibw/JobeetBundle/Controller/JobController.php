<?php
namespace Ibw\JobeetBundle\Controller;

use Ibw\JobeetBundle\Entity\Job;
use Ibw\JobeetBundle\Forms\JobType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

	public function previewAction($token, $company, $position, $location)
	{
		$repo = $this->getDoctrine()->getManager()->getRepository("IbwJobeetBundle:Job");
		$entity = $repo->findOneByToken($token);
		$delete_form = $this->createDeleteForm($token);

		if(!$entity)
		{
			throw $this->createNotFoundException("Job not found!");
		}

		return $this->render("IbwJobeetBundle:Job:show.html.twig", array("entity" => $entity, "delete_form" => $delete_form->createView()));
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

			return $this->redirect($this->generateUrl('ibw_job_preview', array(
					'company' 	=> $entity->getCompanySlug(),
					'location' 	=> $entity->getLocationSlug(),
					'position'	=> $entity->getPositionSlug(),
					'token'		=> $entity->getToken(),
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
			return $this->redirect($this->generateUrl('ibw_job_preview', array(
					'company' 	=> $entity->getCompanySlug(),
					'location' 	=> $entity->getLocationSlug(),
					'position'	=> $entity->getPositionSlug(),
					'token'		=> $entity->getToken(),
				)));
		}

		return $this->render('IbwJobeetBundle:Job:edit.html.twig', array(
	        'entity' => $entity,
	        'edit_form'   => $form->createView(),
	     ));
	}

	public function deleteAction(Request $request, $token)
    {
        $form = $this->createDeleteForm($token);
        $form->handleRequest($request);
 
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IbwJobeetBundle:Job')->findOneByToken($token);
 
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Job entity.');
            }
 
            $em->remove($entity);
            $em->flush();
        }
 
        return $this->redirect($this->generateUrl('ibw_job_index'));
    }

    public function publishAction(Request $request, $token)
    {
        $form = $this->createPublishForm($token);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IbwJobeetBundle:Job')->findOneByToken($token);


            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Job entity.');
            }

            $entity->publish();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Your job is now online for 30 days');
        }

        return $this->redirect($this->generateUrl('ibw_job_preview', array(
            'company' 	=> $entity->getCompanySlug(),
            'location' 	=> $entity->getLocationSlug(),
            'position'	=> $entity->getPositionSlug(),
            'token'		=> $entity->getToken(),
        )));
    }

    public function createPublishForm($token)
    {
        return $this->createFormBuilder(array('token'=>$token))
                    ->add('token','hidden')
                    ->getForm();
    }

	public function createDeleteForm($token)
	{
		return $this->createFormBuilder(array('token' => $token))
                    ->add('token', 'hidden')
					->getForm();
	}
}
?>