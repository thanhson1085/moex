<?php

namespace Moex\CoreBundle\Controller;

use Symfony\Component\Request\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Moex\CoreBundle\Entity\MeOrders;
use Moex\CoreBundle\Entity\MeOrderDriver;
use Moex\CoreBundle\Form\MeOrdersType;
use Moex\CoreBundle\Form\OrderFilterType;
use Moex\CoreBundle\Form\AssignType;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
/**
 * MeOrders controller.
 *
 * @Route("/order")
 */
class MeOrdersController extends Controller
{
    /**
     * Lists all MeOrders entities.
     *
     * @Route("/", name="order")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $filter = $this->getRequest()->getSession()->get('order.filter', new \Moex\CoreBundle\Entity\OrderFilter());

		$translator = $this->get('translator');
        $filterForm = $this->createForm(new OrderFilterType($translator), $filter);
        $filterForm->bindRequest($this->getRequest());
        $this->getRequest()->getSession()->set('order.filter', $filter);
        
        $query = $em->getRepository('MoexCoreBundle:MeOrders')->findByFilterQuery($filter);
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage($this->container->getParameter('moex.pagesize.default'));
        $paginator->setCurrentPage($this->get('request')->query->get('page', 1), false, true);
		
        return array('filterForm' => $filterForm->createView(), 'paginator' => $paginator);
    }

    /**
     * Filter Form.
     *
     * @Route("/filter", name="order_filter")
     * @Template()
     */
    public function filterAction()
    {
        $filter = $this->getRequest()->getSession()->get('order.filter', new \Moex\CoreBundle\Entity\OrderFilter());

		$translator = $this->get('translator');
        $filterForm = $this->createForm(new OrderFilterType($translator), $filter);
        $filterForm->bindRequest($this->getRequest());
        $this->getRequest()->getSession()->set('order.filter', $filter);

        return array('filterForm' => $filterForm->createView());
    }

    /**
     * Finds and displays a MeOrders entity.
     *
     * @Route("/{id}/show", name="order_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MoexCoreBundle:MeOrders')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MeOrders entity.');
        }

		$lat = ($entity->getLat())?$entity->getLat():'21.0267';
		$lng = ($entity->getLng())?$entity->getLng():'105.83659';

		if(!$entity->getLat()){
			$entity->setLat('21.0267');
			$em->persist($entity);
		}
		if(!$entity->getLng()){
			$entity->setLng('105.83659');
			$em->persist($entity);
		}
		
		$assign_drivers = $em->getRepository('MoexCoreBundle:MeDrivers')->findByAssignAndDistance($lat, $lng, $id);
		$unassign_drivers = $em->getRepository('MoexCoreBundle:MeDrivers')->findByUnAssignAndDistance($lat, $lng, $id);
		if(!$assign_drivers){
			$entity->setOrderStatus($this->container->getParameter("moex.order.status.pending"));
			$em->persist($entity);
			$em->flush();
		}
        return array(
            'entity'      => $entity,
            'assign_drivers'     => $assign_drivers,
            'unassign_drivers'     => $unassign_drivers,
			);
    }

    /**
     * Displays a form to create a new MeOrders entity.
     *
     * @Route("/new", name="order_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new MeOrders();
		$translator = $this->get('translator');
        $form   = $this->createForm(new MeOrdersType($translator), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new MeOrders entity.
     *
     * @Route("/create", name="order_create")
     * @Method("post")
     * @Template("MoexCoreBundle:MeOrders:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new MeOrders();
        $request = $this->getRequest();
		$translator = $this->get('translator');
        $form    = $this->createForm(new MeOrdersType($translator), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
        	$created_at = new \DateTime();
        	$updated_at = new \DateTime();
        	$user_id = $this->get('security.context')->getToken()->getUser()->ID; 
			$entity->setCreatedAt($created_at);
			$entity->setUpdatedAt($updated_at);
			$user = $em->getRepository("MoexCoreBundle:MeUsers")->find($user_id);
			$entity->setUser($user);
			$entity->setCustomerId(0);
			if ($entity->getOrderInfo() === null) $entity->setOrderInfo("");
			$validator = $this->get('validator');
			$errors = $validator->validate($entity);
			$status = $this->container->getParameter("moex.order.status.pending");
			$entity->setOrderStatus($status);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('order_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing MeOrders entity.
     *
     * @Route("/{id}/edit", name="order_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MoexCoreBundle:MeOrders')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MeOrders entity.');
        }

		$translator = $this->get('translator');
        $editForm = $this->createForm(new MeOrdersType($translator), $entity);

		$ordermeta = $em->getRepository('MoexCoreBundle:MeOrderGoimon')->findBy(array('order' => $entity, 'metaKey' => 'GOIMON_NOIDUNG'));
		$ordergoimon = $em->getRepository('MoexCoreBundle:MeOrderGoimon')->findBy(array('order' => $entity, 'metaKey' => 'GOIMON_ORDER'));

        return array(
            'entity'      => $entity,
            'ordermeta'      => $ordermeta,
            'ordergoimon'      => $ordergoimon,
            'form'   => $editForm->createView(),
        );
    }

    /**
     * Edits an existing MeOrders entity.
     *
     * @Route("/{id}/update", name="order_update")
     * @Method("post")
     * @Template("MoexCoreBundle:MeOrders:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MoexCoreBundle:MeOrders')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MeOrders entity.');
        }

		$translator = $this->get('translator');
        $editForm   = $this->createForm(new MeOrdersType($translator), $entity);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
        	$updated_at = new \DateTime();
			$entity->setUpdatedAt($updated_at);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('order_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
     * Deletes a MeOrders entity.
     *
     * @Route("/{id}/delete", name="order_delete")
	 *
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

		$em = $this->getDoctrine()->getEntityManager();
		$entity = $em->getRepository('MoexCoreBundle:MeOrders')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find MeOrders entity.');
		}

		$em->remove($entity);
		$em->flush();

        return $this->redirect($this->generateUrl('order'));
    }

    /**
     *
     * @Route("/{order_id}/{driver_id}/assign", name="order_assign")
     * @Template()
     */
    public function assignAction($order_id, $driver_id)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$entity = $em->getRepository('MoexCoreBundle:MeOrders')->find($order_id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MeOrders entity.');
        }
		$driver = $em->getRepository('MoexCoreBundle:MeDrivers')->find($driver_id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MeDrivers entity.');
        }

      	$order_driver = new MeOrderDriver();

        $translator = $this->get('translator');
		$order_driver->setMoney($entity->getPrice());
		$driver_money_rate = $this->container->getParameter("moex.driver.money.rate");
		$order_driver->setDriverMoney($entity->getRoadPrice()*$driver_money_rate[$driver->getDriverType()]);
		$order_driver->setRoadMoney($entity->getRoadPrice());
        $assignForm = $this->createForm(new AssignType($translator), $order_driver);

       	$request = $this->getRequest();	
		if ('POST' === $request->getMethod()){ 
			$od = $em->getRepository('MoexCoreBundle:MeOrderDriver')->findBy(array('order' => $entity, 'driver' => $driver)); 
			if (!$od) {
				$assignForm->bindRequest($request);
				$order_driver->setDriver($driver);
				$order_driver->setOrder($entity);
				$moex_money = (($order_driver->getMoney() - $order_driver->getDriverMoney())>0)?($order_driver->getMoney() - $order_driver->getDriverMoney()):0;
                $order_driver->setMoexMoney($moex_money);
				$created_at = new \DateTime();
				$updated_at = new \DateTime();
				$order_driver->setCreatedAt($created_at);
				$order_driver->setUpdatedAt($updated_at);
				$entity->setOrderStatus($this->container->getParameter("moex.order.status.assigned"));
				$driver->setMoney($driver->getMoney() + $order_driver->getMoney());
				$driver->setDMoney($driver->getDMoney() + $order_driver->getDriverMoney());
				$driver->setMoexMoney($driver->getMoexMoney() + $order_driver->getMoexMoney());
				$em->persist($order_driver);
				$em->persist($entity);
				$em->persist($driver);
				$em->flush();
			}
			return $this->redirect($this->generateUrl('order_show', array( 'id' => $order_id )));
		}
        return array(
			'entity' => $entity,
			'driver' => $driver,
            'assign_form'   => $assignForm->createView(),
        );
	}

    /**
     *
     * @Route("/{order_id}/{driver_id}/unassign", name="order_unassign")
     */
    public function unassignAction($order_id, $driver_id)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$entity = $em->getRepository('MoexCoreBundle:MeOrders')->find($order_id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MeOrders entity.');
        }
		$driver = $em->getRepository('MoexCoreBundle:MeDrivers')->find($driver_id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MeDrivers entity.');
        }
		$order_driver = $em->getRepository('MoexCoreBundle:MeOrderDriver')->findBy(array('order' => $entity, 'driver' => $driver)); 
        if (!$order_driver) {
            throw $this->createNotFoundException('Unable to find MeOrders entity.');
        }
		foreach ($order_driver as $value){
			$driver->setMoney($driver->getMoney() - $value->getMoney());
			$driver->setDMoney($driver->getDMoney() - $value->getDriverMoney());
			$driver->setMoexMoney($driver->getMoexMoney() - $value->getMoexMoney());
			$em->remove($value);
			$em->persist($driver);
		}
		$em->flush();	

		return $this->redirect($this->generateUrl('order_show', array( 'id' => $order_id )));
	}

    /**
     *
     * @Route("/{id}/done", name="order_done")
     */
    public function doneAction($id)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$entity = $em->getRepository('MoexCoreBundle:MeOrders')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MeOrders entity.');
        }
		if($entity->getOrderStatus() == $this->container->getParameter("moex.order.status.done")){
			return $this->redirect($this->generateUrl('order_show', array( 'id' => $id )));
		}
		$entity->setOrderStatus($this->container->getParameter("moex.order.status.done"));
		$em->persist($entity);
		$em->flush();
		return $this->redirect($this->generateUrl('order_show', array( 'id' => $id )));
	}

    /**
     *
     * @Route("/{id}/undone", name="order_undone")
     */
    public function undoneAction($id)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$entity = $em->getRepository('MoexCoreBundle:MeOrders')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MeOrders entity.');
        }
		if($entity->getOrderStatus() == $this->container->getParameter("moex.order.status.assigned")){
			return $this->redirect($this->generateUrl('order_show', array( 'id' => $id )));
		}
		$entity->setOrderStatus($this->container->getParameter("moex.order.status.assigned"));
		$em->persist($entity);
		$em->flush();
		return $this->redirect($this->generateUrl('order_show', array( 'id' => $id )));
	}

    /**
     *
     * @Route("/{order_id}/{driver_id}/quickview", name="order_quickview")
     * @Template()
     */
	public function quickviewAction($order_id, $driver_id){
        $em = $this->getDoctrine()->getEntityManager();
        $done_order = $em->getRepository('MoexCoreBundle:MeDrivers')
                            ->findByStatusAndDriverId($this->container->getParameter('moex.order.status.done'), $driver_id);
        $assigned_order = $em->getRepository('MoexCoreBundle:MeDrivers')
                            ->findByStatusAndDriverId($this->container->getParameter('moex.order.status.assigned'), $driver_id);
        return array(
			'driver_id' => $driver_id,
			'order_id' => $order_id,
            'done_order'  => $done_order,
            'assigned_order' => $assigned_order
            );

	}
    /**
     *
     * @Route("/{order_id}/{driver_id}/info", name="order_info")
     * @Template()
     */
	public function infoAction($order_id, $driver_id){
		$em = $this->getDoctrine()->getEntityManager();
		$entity = $em->getRepository("MoexCoreBundle:MeOrders")->find($order_id);
		$username = $em->getRepository('MoexCoreBundle:MeOrders')->findOneUserById($entity->getUserId());
		if(!$entity){
			throw $this->createNotFoundException('Unable to find Orders entity.');
		}
		$lat = $entity->getLat();
		$lng = $entity->getLng();
		
		$assign_drivers = $em->getRepository('MoexCoreBundle:MeDrivers')->findByAssignAndDistance($lat, $lng, $order_id);
		$unassign_drivers = $em->getRepository('MoexCoreBundle:MeDrivers')->findByUnAssignAndDistance($lat, $lng, $order_id);
        return array(
            'entity'      => $entity,
            'assign_drivers'     => $assign_drivers,
            'unassign_drivers'     => $unassign_drivers,
			'username'   => $username,
			);
	}


    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

	function distance($lat1, $lng1, $lat2, $lng2, $miles = false)
	{
		$pi80 = M_PI / 180;
		$lat1 *= $pi80;
		$lng1 *= $pi80;
		$lat2 *= $pi80;
		$lng2 *= $pi80;

		$r = 6372.797; // mean radius of Earth in km
		$dlat = $lat2 - $lat1;
		$dlng = $lng2 - $lng1;
		$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
		$km = $r * $c;

		return ($miles ? ($km * 0.621371192) : $km);
	}
}
