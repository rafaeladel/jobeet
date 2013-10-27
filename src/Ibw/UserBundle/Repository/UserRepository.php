<?php

namespace Ibw\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Ibw\UserBundle\IbwUserBundle;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserRepository extends EntityRepository implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        $q = $this->createQueryBuilder('u')
                    ->select('u, r')
                    ->leftJoin('u.roles', 'r')
                    ->where('u.username = :username')
                    ->setParameter('username', $username)
                    ->getQuery();
        try
        {
            $user = $q->getSingleResult();
        }
        catch (NoResultException $e)
        {
            $message = sprintf('Unable to find active admin identified by %s', $username);
            throw new UsernameNotFoundException($message, 0, $e);
        }
        return $user;
    }


    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if(!$this->supportsClass($class))
        {
            $message = sprintf('Unsupported class type : %s', $class);
            throw new UnsupportedUserException($message);
        }

        //If the serialize() in User is to serialize Id, getUsername() will return nothing!
        //So serialize() should serialize the username,
        //or use $this->find($user->getId()); instead
        return $this->loadUserByUsername($user->getUsername());
    }


    public function supportsClass($class)
    {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }
}
