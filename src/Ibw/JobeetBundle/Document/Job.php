<?php 
namespace Ibw\JobeetBundle\Document;

use Ibw\JobeetBundle\Document\Category;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="jobs", repositoryClass="Ibw\JobeetBundle\Repository\JobRepository")
 * @Assert\GroupSequence({"Form", "Job"})
 */
class Job
{
	/**
	 * @MongoDB\Id(strategy="AUTO")
	 */
	protected $id;

	/**
     * @MongoDB\String
     * @Assert\NotBlank(groups={"Form"})
     * @Assert\Choice(callback="getTypeValues", groups={"Form"})
	 */
	protected $type;

	/**
     * @MongoDB\String
     * @Assert\NotBlank(groups={"Form"})
	 */
	protected $company;

	/**
     * @MongoDB\String
	 */
	protected $logo;

    /**
     * @Assert\Image(groups={"Form"})
     */
    protected $file;

	/**
     * @MongoDB\String
     * @Assert\Url(groups={"Form"})
	 */
	protected $url;

	/**
     * @MongoDB\String
     * @Assert\NotBlank(groups={"Form"})
	 */
	protected $position;

	/**
     * @MongoDB\String
     * @Assert\NotBlank(groups={"Form"})
	 */
	protected $location;

	/**
//	 * 
     * @MongoDB\String
     * @Assert\NotBlank(groups={"Form"})
	 */
	protected $description;

	/**
//	 * 
     * @MongoDB\String
     * @Assert\NotBlank(groups={"Form"})
	 */
	protected $how_to_apply;

	/**
//	 * 
     * @MongoDB\String
     * @Assert\NotBlank()
	 */
	protected $token;

	/**
//	 * 
     * @MongoDB\Boolean
	 */
	protected $is_public;

	/**
//	 * 
     * @MongoDB\Boolean
	 */
	protected $is_activated;

	/**
//	 * 
     * @MongoDB\String
     * @Assert\NotBlank(groups={"Form"})
     * @Assert\Email(groups={"Form"})
	 */
	protected $email;

	/**
//	 * 
     * @MongoDB\Date
	 */
	protected $expires_at;

	/**
//	 * 
     * @MongoDB\Date
	 */
	protected $created_at;

	/**
//	 * 
     * @MongoDB\Date
	 */
	protected $updated_at;

	/**
     * @MongoDB\ReferenceOne(targetDocument="Category", inversedBy="jobs", simple=true)
     * @Assert\NotBlank(groups={"Form"})
	 */
	protected $category;

	/**
	 * @MongoDB\PrePersist()
	 */
	public function setCreatedAtValue()
	{
		if(!$this->getCreatedAt())
		{
			$this->setCreatedAt(new \DateTime());
		}
	}

    /**
     * @MongoDB\PrePersist()
     */
    public function setExipresAtValue()
    {
        if(!$this->getExpiresAt())
        {
            $now = $this->getCreatedAt() ? $this->getCreatedAt()->format("U") : time();
            $this->setExpiresAt(new \DateTime(date('Y-m-d H:i:s', $now + 86400 * 30)));
//            $this->setExpiresAt(\DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime("+1 month"))->format("Y-m-d H:i:s")));
        }
    }

	/**
	 * @MongoDB\PreUpdate()
	 */
	public function setUpdatedAtValue()
	{
		$this->setUpdatedAt(new \DateTime());
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
     * Set type
     *
     * @param string $type
     * @return Job
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    public static function getTypes()
    {
        return array('full-time' => 'Full time', 'part-time' => 'Part time', 'freelance' => 'Freelance');
    }

    public static function getTypeValues()
    {
        return array_keys(self::getTypes());
    }

    /**
     * Set company
     *
     * @param string $company
     * @return Job
     */
    public function setCompany($company)
    {
        $this->company = $company;
    
        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set logo
     *
     * @param string $logo
     * @return Job
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    
        return $this;
    }

    /**
     * Get logo
     *
     * @return string 
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Job
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
     * Set position
     *
     * @param string $position
     * @return Job
     */
    public function setPosition($position)
    {
        $this->position = $position;
    
        return $this;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return Job
     */
    public function setLocation($location)
    {
        $this->location = $location;
    
        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Job
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set how_to_apply
     *
     * @param string $howToApply
     * @return Job
     */
    public function setHowToApply($howToApply)
    {
        $this->how_to_apply = $howToApply;
    
        return $this;
    }

    /**
     * Get how_to_apply
     *
     * @return string 
     */
    public function getHowToApply()
    {
        return $this->how_to_apply;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Job
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
     * Set is_public
     *
     * @param boolean $isPublic
     * @return Job
     */
    public function setIsPublic($isPublic)
    {
        $this->is_public = $isPublic;
    
        return $this;
    }

    /**
     * Get is_public
     *
     * @return boolean 
     */
    public function getIsPublic()
    {
        return $this->is_public;
    }

    /**
     * Set is_activated
     *
     * @param boolean $isActivated
     * @return Job
     */
    public function setIsActivated($isActivated)
    {
        $this->is_activated = $isActivated;
    
        return $this;
    }

    /**
     * Get is_activated
     *
     * @return boolean 
     */
    public function getIsActivated()
    {
        return $this->is_activated;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Job
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
     * Set expires_at
     *
     * @param \DateTime $expiresAt
     * @return Job
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expires_at = $expiresAt;
    
        return $this;
    }

    /**
     * Get expires_at
     *
     * @return \DateTime 
     */
    public function getExpiresAt()
    {
        return $this->expires_at;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Job
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
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Job
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    
        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set category
     *
     * @param Category $category
     * @return Job
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function getCompanySlug()
    {
        return Jobeet::slugify($this->getCompany());
    }

    public function getPositionSlug()
    {
        return Jobeet::slugify($this->getPosition());
    }

    public function getLocationSlug()
    {
        return Jobeet::slugify($this->getLocation());
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function getUploadDir()
    {
        return 'uploads/jobs';
    }

    public function getRootUploadDir()
    {
        return "/../../../../web/". $this->getUploadDir();
    }

    public function getWebPath()
    {
        return $this->logo === null ? null : $this->getUploadDir()."/".$this->logo;
    }

    public function getAbsolutePath()
    {
        return $this->logo === null ? null : $this->getRootUploadDir()."/".$this->logo;
    }

    /**
     * @MongoDB\PostLoad()
     */
    public function postLoad()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * WARNING!! PreUpdate no fired since $file is not managed by Doctrine.
     * SOLUTION: use PostLoad() Event.
     * @MongoDB\PrePersist()
     * @MongoDB\PreUpdate()
     */
    public function preUpload()
    {
        if($this->file !== null)
        {
            $this->logo = uniqid().".".$this->file->guessExtension();
        }
    }

    /**
     * @MongoDB\PostPersist()
     * @MongoDB\PostUpdate()
     */
    public function upload()
    {
        if($this->file === null)
        {
            return;
        }

        if(!file_exists($this->getUploadDir()))
        {
            mkdir($this->getUploadDir(),0777, true);
        }

        $this->file->move($this->getUploadDir(), $this->logo);
        unset($this->file);
    }

    /**
     * @MongoDB\PostRemove()
     */
    public function removeUpload()
    {
        if($this->getAbsolutePath() !== null){
            if(file_exists($this->getAbsolutePath()))
            {
                unlink($this->getAbsolutePath());
            }
        }
    }

    /**
     * @MongoDB\PrePersist()
     */
    public function setTokenValue()
    {
        if(!$this->getToken())
        {
            $this->setToken(sha1($this->getEmail().rand(11111,99999)));
        }
    }

    public function getDaysBeforeExpiration()
    {
        return ceil(($this->getExpiresAt()->format('U') - time()) / 86400);
    }

    public function expiresSoon()
    {
        return $this->getDaysBeforeExpiration() < 5;
    }

    public function isExpired()
    {
        return $this->getDaysBeforeExpiration() < 0;
    }

    public function publish()
    {
        $this->setIsActivated(true);
        return;
    }

    public function extend()
    {
        if(!$this->expiresSoon())
        {
            return false;
        }
        else
        {
//            $this->expires_at = new \DateTime('+1 month');
//            $this->setExpiresAt(\DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime("+1 month"))->format("Y-m-d H:i:s")));
            $this->expires_at = new \DateTime(date('Y-m-d H:i:s', time() + 86400 * 30));
            return true;
        }
    }

}