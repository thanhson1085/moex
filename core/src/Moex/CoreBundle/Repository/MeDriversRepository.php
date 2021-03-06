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
class MeDriversRepository extends EntityRepository
{
    public function findByFilterQuery(\Moex\CoreBundle\Entity\DriverFilter $filter)
    {
        $query = $this->createQueryBuilder('d')
                              ->where('1 = 1');

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

	public function findByAssignAndDistance($latitude, $longitude, $order_id, $limit = 15){
		$em = $this->getEntityManager();
		$rsm = new ResultSetMapping;
		$rsm->addEntityResult('Moex\CoreBundle\Entity\MeDrivers', 'd');
		$rsm->addEntityResult('Moex\CoreBundle\Entity\MeOrderDriver', 'od'); 
		$rsm->addFieldResult('d','id','id');
		$rsm->addFieldResult('d','lat','lat');
		$rsm->addFieldResult('d','lng','lng');
		$rsm->addFieldResult('d','phone','phone');
		$rsm->addFieldResult('d','driverName','driverName');
		$rsm->addFieldResult('d','position','position');
		$rsm->addScalarResult('distance','distance');
		$sql = 'SELECT d.id AS id, d.lat AS lat, d.lng AS lng, d.phone AS phone, d.driver_name AS driverName, d.position AS position,'
			.' (((ACOS(SIN('.$latitude.' * PI() / 180) * SIN(d.lat * PI() / 180)'
			.' + COS('.$latitude.' * PI() / 180) * COS(d.lat * PI() / 180)'
			.' * COS(('.$longitude.' - d.lng) * PI() / 180))* 180 / PI())'
			.' * 60 * 1.1515)*1.609344) AS distance'
			.' FROM me_drivers d INNER JOIN me_order_driver od ON od.driver_id = d.id'
			.' WHERE od.order_id = '.$order_id.' GROUP BY id HAVING distance < 10'
			.' ORDER BY distance ASC LIMIT '.$limit;
		return $em->createNativeQuery($sql, $rsm)->getResult();
	}

	public function findByUnAssignAndDistance($latitude, $longitude, $order_id, $limit = 15){
		$em = $this->getEntityManager();
		$rsm = new ResultSetMapping;
		$rsm->addEntityResult('Moex\CoreBundle\Entity\MeDrivers', 'd');
		$rsm->addEntityResult('Moex\CoreBundle\Entity\MeOrderDriver', 'od');
		$rsm->addFieldResult('d','id','id');
		$rsm->addFieldResult('d','lat','lat');
		$rsm->addFieldResult('d','lng','lng');
		$rsm->addFieldResult('d','phone','phone');
		$rsm->addFieldResult('d','driverName','driverName');
		$rsm->addFieldResult('d','position','position');
		$rsm->addScalarResult('distance','distance');
		$sql = 'SELECT d.id AS id, d.lat AS lat, d.lng AS lng, d.phone AS phone, d.driver_name AS driverName, d.position AS position,'
			.' (((ACOS(SIN('.$latitude.' * PI() / 180) * SIN(lat * PI() / 180)'
			.' + COS('.$latitude.' * PI() / 180) * COS(lat * PI() / 180)'
			.' * COS(('.$longitude.' - lng) * PI() / 180))* 180 / PI())'
			.' * 60 * 1.1515)*1.609344) AS distance'
			.' FROM me_drivers d WHERE d.id NOT IN (SELECT od.driver_id FROM me_order_driver od WHERE d.id = od.driver_id AND od.order_id = '.$order_id.') '
			.' GROUP BY id HAVING distance < 10'
			.' ORDER BY distance ASC LIMIT '.$limit;
		return $em->createNativeQuery($sql, $rsm)->getResult();
	}

    public function findByStatusAndDriverId($status, $driver_id, $limit = 15)
    {
		$em = $this->getEntityManager();
		$rsm = new ResultSetMapping;
		$rsm->addEntityResult('Moex\CoreBundle\Entity\MeOrders', 'o');
		$rsm->addEntityResult('Moex\CoreBundle\Entity\MeOrderDriver', 'od');
        $rsm->addFieldResult('o','id','id');
        $rsm->addFieldResult('o','orderName','orderName');
        $rsm->addFieldResult('o','price','price');
        $rsm->addFieldResult('o','phone','phone');
		$sql = "SELECT o.id AS id, o.order_name AS orderName, o.price AS price, o.phone AS phone"
			." FROM me_orders AS o INNER JOIN me_order_driver od ON od.order_id = o.id WHERE o.order_status = '".$status."'"
			." AND od.driver_id = ".$driver_id." LIMIT ".$limit;
		return $em->createNativeQuery($sql, $rsm)->getResult();

    }

}
