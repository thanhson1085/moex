<?php

namespace Moex\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;


/**
 * MeOrderDriverRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MeOrderDriverRepository extends EntityRepository
{
    public function findByFilterQuery(\Moex\CoreBundle\Entity\DriverFilter $filter, \Moex\CoreBundle\Entity\OrderFilter $order_filter)
    {
        $query = $this->createQueryBuilder('od')
                              ->where('1 = 1');

        $query = $query->innerJoin('od.driver', 'd');

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

        $query = $query->innerJoin('od.order', 'o');

        if ($order_filter->getPhone() != null) {
            $query = $query->andWhere('o.phone LIKE :phone')
                            ->setParameter('phone', "%".$order_filter->getPhone()."%");
        }

        if ($order_filter->getOrderFrom() != null) {
            $query = $query->andWhere('o.orderFrom LIKE :orderfrom')
                            ->setParameter('orderfrom', "%".$order_filter->getOrderFrom()."%");
        }

        if ($order_filter->getOrderTo() != null) {
            $query = $query->andWhere('o.orderTo LIKE :orderto')
                            ->setParameter('orderto', "%".$order_filter->getOrderTo()."%");
        }

        if ($order_filter->getOrderName() != null) {
            $query = $query->andWhere('o.orderName LIKE :ordername')
                            ->setParameter('ordername', "%".$order_filter->getOrderName()."%");
        }

        if ($order_filter->getOrderInfo() != null) {
            $query = $query->andWhere('o.orderInfo LIKE :orderinfo')
                            ->setParameter('orderinfo', $order_filter->getOrderInfo()."%");
        }
        return $query->getQuery();
    }
}
