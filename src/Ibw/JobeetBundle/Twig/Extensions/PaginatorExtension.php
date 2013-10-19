<?php
namespace Ibw\JobeetBundle\Twig\Extensions;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Ibw\JobeetBundle\Utils\Paginator;

class PaginatorExtension extends \Twig_Extension
{
	public function getFunctions()
	{
		return array(
			new \Twig_SimpleFunction('Paginator', array($this, 'init_paginator'), array('is_safe' => array('html'))),
		);
	}

	private $request;

	public function __construct(RouterInterface $router, Paginator $paginator)
	{
		$this->router = $router;
		$this->paginator = $paginator;
	}

	public function setRequest(Request $request = null)
	{
		$this->request = $request;
	}

	public function init_paginator(array $parameters = array())
	{
		if($this->request === null)
		{
			return "";
		}
		if(!empty($parameters))
		{
			if(!empty(array_diff(array_keys($parameters), array('pageParam', 'wrapperClass'))))
			{
				throw new \Exception("Paginator only accepts 2 elements in array: pageParam and wrapperClass.");	
			}
		}
		
		$this->pageParam = array_key_exists("pageParam", $parameters) ? $parameters["pageParam"] : "page";
		$this->wrapperClass = array_key_exists("wrapperClass", $parameters) ? $parameters["wrapperClass"] : "";	
		
		return $this->processAnchors($this->paginator);
	}

	/**
	 * Puts each anchor tag together to form the whole paginator block
	 * @param  Paginator 	$paginator 	Given Paginator class from the view
	 * @param  string 		$divClass  	CSS Class for div wrapper
	 */
	private function processAnchors($paginator)
	{	
		$result = "";
		if($paginator->pages_count <= 1)
		{	
			return $result;
		}
			
	    $result = "<div class=".$this->wrapperClass.">";

        if($paginator->hasPrev())
        {
            $result .= $this->processRoute($paginator->prevPage(), "&lt;");
        }

        for($page = 1; $page <= $paginator->pages_count; $page++){
            if($page == $paginator->current_page)
            {
                $result .= " <strong>{$page}</strong>";
            }
            else 
            {
                $result .= $this->processRoute($page, $page);
            }
        }

        if($paginator->hasNext())
        {
            $result .= $this->processRoute($paginator->nextPage(), "&gt;");
        }
	    
	    $result .= "</div>";

	    return $result;
	}

	/**
	 * Generate each anchor tag with a given parameters
	 * @param  int 		$newPage	The "page" argument that's inside the route
	 * @param  string 	$text    	Text of the anchor tag	
	 */
	private function processRoute($newPage, $text)
	{

		//Getting the current route, Including parameters array
		$currentRoute = $this->request->getPathInfo();

		//Extracting current route parameters
		$params = $this->router->match($currentRoute);

		//Getting the route name needed by RouterInterface::generate()
		$currentRouteName = $params['_route'];

		//Removing unneeded parameters (eg. _controller, _route)
		//Generating new array with needed parameters, Including the new page number	
		$filteredParams = array();
		foreach($params as $key => $value)
		{
			if(!preg_match('/^_/', $key))
			{   
				if($key == $this->pageParam) $value = $newPage;
				$filteredParams[$key] = $value;
			}
		}

		$result = " <a href=\"".$this->router->generate($currentRouteName, $filteredParams)."\">".$text."</a>";
		return $result;
	}

	public function getName()
	{
		return 'PaginatorExtension';
	}
}
?>