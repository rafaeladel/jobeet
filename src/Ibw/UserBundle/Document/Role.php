<?php
namespace Ibw\UserBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Class Role
 * @MongoDB\Document(collection="roles", repositoryClass="Ibw\UserBundle\Repository\RoleRepository")
 */
class Role implements RoleInterface
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
     * @MongoDB\String
     */
    protected $role;

    /**
     * @MongoDB\ReferenceMany(targetDocument="User", mappedBy="roles", simple=true)
     */
    protected $users;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    public function getRole()
    {
        return $this->role;
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
     * @return Role
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
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Add users
     *
     * @param \Ibw\UserBundle\Entity\User $users
     * @return Role
     */
    public function addUser(\Ibw\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;
    
        return $this;
    }

    /**
     * Remove users
     *
     * @param \Ibw\UserBundle\Entity\User $users
     */
    public function removeUser(\Ibw\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}