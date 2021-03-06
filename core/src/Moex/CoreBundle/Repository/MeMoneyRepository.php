<?php

namespace Moex\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;


/**
 * MeDriversRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MeMoneyRepository extends EntityRepository
{
    public function findByFilterQuery(\Moex\CoreBundle\Entity\DriverFilter $filter)
    {
        $query = $this->createQueryBuilder('m')
                              ->where('1 = 1');
        $query = $query->innerJoin('m.driver', 'd');

        if ($filter->getPhone() != null) {
			$query = $query->andWhere('d.phone LIKE :phone')
							->setParameter('phone', "%".$filter->getPhone()."%");
        }

        if ($filter->getPosition() != null) {
            $query = $query->andWhere('d.position LIKE :position')
                            ->setParameter('position', "%".$filter->getPostion()."%");
        }

        if ($filter->getDriverName() != null) {
            $query = $query->andWhere('d.driverName LIKE :drivername')
                            ->setParameter('drivername', "%".$filter->getDriverName()."%");
        }

        if ($filter->getDriverInfo() != null) {
            $query = $query->andWhere('d.driverInfo LIKE :driverinfo')
                            ->setParameter('driverinfo', "%".$filter->getDriverInfo()."%");
        }

        return $query->getQuery();
    }
}
