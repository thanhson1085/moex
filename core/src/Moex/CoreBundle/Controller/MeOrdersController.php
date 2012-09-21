<?php

namespace Moex\CoreBundle\Controller;

use Symfony\Component\Request\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Moex\CoreBundle\Entity\MeOrders;
use Moex\CoreBundle\Form\MeOrdersType;
use Moex\CoreBundle\Form\OrderFilterType;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
/**
 * MeOrders controller.
 *
 * @Route("/order")
 */
class MeOrdersController extends Controller
{
	const STATUS_PENDING = 'PENDING';
	const STATUS_ASSIGNED = 'ASSIGNED';
	const STATUS_DONE = 'DONE';
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
		$username = $em->getRepository('MoexCoreBundle:MeOrders')->findOneUserById($entity->getUserId());

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MeOrders entity.');
        }

		$lat = $entity->getLat();
		$lng = $entity->getLng();
		
		$assign_drivers = $em->getRepository('MoexCoreBundle:MeDrivers')->findByAssignAndDistance($lat, $lng, $id);
		$unassign_drivers = $em->getRepository('MoexCoreBundle:MeDrivers')->findByUnAssignAndDistance($lat, $lng, $id);
		if(!$assign_drivers){
			$entity->setOrderStatus(self::STATUS_PENDING);
			$em->persist($entity);
			$em->flush();
		}
        return array(
            'entity'      => $entity,
            'assign_drivers'     => $assign_drivers,
            'unassign_drivers'     => $unassign_drivers,
			'username'   => $username,
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
			$entity->setUserId($user_id);
			if ($entity->getOrderInfo() === null) $entity->setOrderInfo("");
			$validator = $this->get('validator');
			$errors = $validator->validate($entity);
			$status = self::STATUS_PENDING; 
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

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
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
            'edit_form'   => $editForm->createView(),
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
     */
    public function assignAction($order_id, $driver_id)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$entity = $em->getRepository('MoexCoreBundle:MeOrders')->find($order_id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MeOrders entity.');
        }
		$order_driver = $em->getRepository('MoexCoreBundle:MeOrderDriver')->findBy(array('orderId' => $order_id, 'driverId' => $driver_id)); 
        if (!$order_driver) {
			$order_driver = new \Moex\CoreBundle\Entity\MeOrderDriver;
			$order_driver->setDriverId($driver_id);
			$order_driver->setOrderId($order_id);
			$created_at = new \DateTime();
			$updated_at = new \DateTime();
			$order_driver->setCreatedAt($created_at);
			$order_driver->setUpdatedAt($updated_at);
			$em->persist($order_driver);
			$entity->setOrderStatus(self::STATUS_ASSIGNED);
			$em->persist($entity);
			$em->flush();
		}
		return $this->redirect($this->generateUrl('order_show', array( 'id' => $order_id )));
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
		$order_driver = $em->getRepository('MoexCoreBundle:MeOrderDriver')->findBy(array('orderId' => $order_id, 'driverId' => $driver_id)); 
        if (!$order_driver) {
            throw $this->createNotFoundException('Unable to find MeOrders entity.');
        }
		foreach ($order_driver as $value){
			$em->remove($value);
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
		$entity->setOrderStatus(self::STATUS_DONE);
		$em->persist($entity);
		$order_driver = $em->getRepository('MoexCoreBundle:MeOrderDriver')->findBy(array('orderId' => $id)); 
		$count = count($order_driver);
		$price = floor($entity->getPrice()/$count);
		$updated_at = new \DateTime();
		foreach ($order_driver as $value){
			$value->setMoney($price);
			$value->setUpdatedAt($updated_at);
			$driver = $em->getRepository('MoexCoreBundle:MeDrivers')->find($value->getDriverId());
			$driver->setMoney($driver->getMoney() + $price);
			$em->persist($driver);
			$em->persist($value);
		}
		$em->flush();
		return $this->redirect($this->generateUrl('order_show', array( 'id' => $id )));
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
