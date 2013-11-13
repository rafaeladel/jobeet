<?php
namespace Ibw\JobeetBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class AffiliateRepository extends EntityRepository
{
    public function getForToken($token)
    {
        $q = $this->createQueryBuilder('a')
                    ->select('a')
                    ->where('a.is_active = :active')
                    ->setParameter('active', 1)
                    ->andWhere('a.token = :token')
                    ->setParameter('token', $token)
                    ->setMaxResults(1);

        try
        {
            $affiliate = $q->getQuery()->getSingleResult();
        }
        catch (NoResultException $e)
        {
            $affiliate = null;
        }
        return $affiliate;
    }
}
?>