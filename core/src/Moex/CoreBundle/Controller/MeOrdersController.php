<?php

namespace Moex\CoreBundle\Controller;

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

        $deleteForm = $this->createDeleteForm($id);

		$lat = $entity->getLat();
		$lng = $entity->getLng();
		
		$drivers = $em->getRepository('MoexCoreBundle:MeDrivers')->findByDistance($lat, $lng);
        return array(
            'entity'      => $entity,
            'drivers'     => $drivers,
			'username'   => $username,
            'delete_form' => $deleteForm->createView(),
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
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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
        $deleteForm = $this->createDeleteForm($id);

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
            'delete_form' => $deleteForm->createView(),
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
