<?php
namespace Ibw\JobeetBundle\Entity;
	
use Doctrine\Common\Collections\ArrayCollection;
use Ibw\JobeetBundle\Entity\Category;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Ibw\JobeetBundle\Repository\AffiliateRepository")
 * @ORM\Table(name="affiliates")
 * @ORM\HasLifecycleCallbacks
 */
class Affiliate
{

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $url;

	/**
	 * @ORM\Column(type="string", length=255, unique=true)
	 */
	protected $email;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $token;

	/**
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	protected $is_active;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $created_at;

	/**
	 * @ORM\ManyToMany(targetEntity="Category")
	 * @ORM\JoinTable(name="category_affiliate",
	 * 			joinColumns={ @ORM\JoinColumn(name="affiliate_id", referencedColumnName="id") },
	 * 			inverseJoinColumns={ @ORM\JoinColumn(name="category_id", referencedColumnName="id") }
	 * )
	 */
	protected $categories;

	/**
	 * @ORM\PrePersist
	 */
	public function setCreatedAtValue()
	{
		if(!$this->getCreatedAt())
		{
			$this->setCreatedAt(new \DateTime());
		}	
	}

    /**
     * @ORM\PrePersist
     */
    public function setTokenValue()
    {
        if(!$this->getToken())
        {
            $this->setToken(sha1($this->getEmail().rand(11111,99999)));
        }
        return $this;
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
    public function addCategories(Category $categories)
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