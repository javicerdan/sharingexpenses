<?php

namespace Javicode\SharingExpensesBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ExpenseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ExpenseRepository extends EntityRepository
{
    public function getTotalAmountSpentInGroup($group_id)
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->setParameter('sharinggroup_id', $group_id)
            ->groupBy("sum")
            ->getResult();
    }

    public function getAmountSpentByUserInGroup($user_id, $group_id)
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->setParameter('sharinggroup_id', $group_id)
            ->setParameter('user_id', $user_id)
            ->groupBy("sum")
            ->getResult();
    }
}
