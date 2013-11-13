<?php
namespace Ibw\JobeetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    public function listAction(Request $request, $token)
    {
        $em = $this->getDoctrine()->getManager();
        $jobs = array();

        $affiliate = $em->getRepository("IbwJobeetBundle:Affiliate")->getForToken($token);

        if(!$affiliate)
        {
            throw $this->createNotFoundException("This affiliate does not exists");
        }

        $activeJobs = $em->getRepository("IbwJobeetBundle:Job")->getActiveJobs(null, null, null, $affiliate->getId());

        foreach($activeJobs as $job)
        {
            $url = $this->get("router")->generate("ibw_job_show", array(
                "company"   => $job->getCompanySlug(),
                "location"  => $job->getLocationSlug(),
                "id"        => $job->getId(),
                "position"  => $job->getPositionSlug()
            ), true);

            $jobs[$url] = $job->asArray($request->getHost());
        }

        $format = $request->getRequestFormat();
        $jsonData = json_encode($jobs);

        if($format == "json")
        {
            $headers = array('Content-Type' => 'application/json');
            $response = new Response($jsonData, 200, $headers);

            return $response;
        }

        return $this->render("IbwJobeetBundle:Api:list.{$format}.twig", array('jobs'=>$jobs));

    }
}
?>