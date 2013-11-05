<?php
namespace Ibw\JobeetBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Ibw\JobeetBundle\Document\Affiliate;
use Ibw\JobeetBundle\Document\Job;
use Ibw\JobeetBundle\Utils\Jobeet;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="categories", repositoryClass="Ibw\JobeetBundle\Repository\CategoryRepository")
 */
class Category 
{

	/**
	 * @MongoDB\Id(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @MongoDB\String
	 */
	protected $name;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Job", mappedBy="category", simple=true, cascade={"persist"})
     */
    protected $jobs;

	/**
     * @MongoDB\ReferenceMany(targetDocument="Affiliate", mappedBy="categories", simple=true)
	 */
	protected $affiliates;

    /**
     * @MongoDB\String
     */
    protected $slug;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->jobs = new ArrayCollection();
        $this->affiliates = new ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add jobs
     *
     * @param Job $jobs
     * @return Category
     */
    public function addJob(Job $jobs)
    {
        $this->jobs[] = $jobs;
    
        return $this;
    }

    /**
     * Remove jobs
     *
     * @param Job $jobs
     */
    public function removeJob(Job $jobs)
    {
        $this->jobs->removeElement($jobs);
    }

    /**
     * Get jobs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * Add affiliates
     *
     * @param Affiliate $affiliates
     * @return Category
     */
    public function addAffiliate(Affiliate $affiliates)
    {
        $this->affiliates[] = $affiliates;
    
        return $this;
    }

    /**
     * Remove affiliates
     *
     * @param Affiliate $affiliates
     */
    public function removeAffiliate(Affiliate $affiliates)
    {
        $this->affiliates->removeElement($affiliates);
    }

    /**
     * Get affiliates
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAffiliates()
    {
        return $this->affiliates;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @MongoDB\PrePersist
     * @MongoDB\PreUpdate
     */
    public function setSlugValue()
    {
        $this->setSlug(Jobeet::slugify($this->getName()));
    }

    public function __toString()
    {
        return $this->getName();
    }
}