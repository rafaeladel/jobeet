<?php
namespace Ibw\JobeetBundle\Document;
	
use Doctrine\Common\Collections\ArrayCollection;
use Ibw\JobeetBundle\Document\Category;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="affiliates")
 */
class Affiliate
{

	/**
	 * @MongoDB\Id(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @MongoDB\String
	 */
	protected $url;

	/**
     * @MongoDB\String
	 */
	protected $email;

	/**
     * @MongoDB\String
	 */
	protected $token;

	/**
     * @MongoDB\Boolean
	 */
	protected $is_active;

	/**
     * @MongoDB\Date
	 */
	protected $created_at;

	/**
     * @MongoDB\ReferenceMany(targetDocument="Category", inversedBy="affiliates", simple=true)
	 */
	protected $categories;

	/**
	 * @MongoDB\PrePersist
	 */
	public function setCreatedAtValue()
	{
		if(!$this->getCreatedAt())
		{
			$this->setCreatedAt(new \DateTime());
		}	
	}
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
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
     * Set url
     *
     * @param string $url
     * @return Affiliate
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Affiliate
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Affiliate
     */
    public function setToken($token)
    {
        $this->token = $token;
    
        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return Affiliate
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;
    
        return $this;
    }

    /**
     * Get is_active
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Affiliate
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    
        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Add categories
     *
     * @param Category $categories
     * @return Affiliate
     */
    public function addCategorie(Category $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param Category $categories
     */
    public function removeCategorie(Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }
}