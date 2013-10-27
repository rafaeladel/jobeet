<?php
namespace Ibw\UserBundle\Entity;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Ibw\UserBundle\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=40)
     */
    protected $salt;

    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $password;

    public function __construct(ContainerInterface $container)
    {
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
        $this->container = $container;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword($password)
    {
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($this);
        $this->password = $encoder->encodePassword($password, $this->getSalt());
        return $this;
    }

    /**
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        return array('ROLE_ADMIN');
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * Serialize the user object to be passed to refreshUser() method in the user provider
     * Now after serialization, user has: id=null, username=$this->username, password=null, salt=null
     * @return string
     */
    public function serialize()
    {
        return serialize(array($this->username));
    }

    public function unserialize($serialized)
    {
        list($this->username) = unserialize($serialized);
    }


    public function getId()
    {
        return $this->id;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }
}