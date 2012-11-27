<?php
namespace Moex\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Moex\CoreBundle\Entity\MeDrivers;
use Moex\CoreBundle\Entity\MeMoney;
use Moex\CoreBundle\Form\MeDriversType;
use Moex\CoreBundle\Form\ChargeType;
use Moex\CoreBundle\Form\DriverFilterType;
use Moex\CoreBundle\Form\OrderFilterType;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;

/**
 * MeDrivers controller.
 *
 * @Route("/money")
 */
class MeMoneyController extends Controller
{
    /**
     * Finds and displays a MeMoney entity.
     *
     * @Route("/", name="money")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $filter = $this->getRequest()->getSession()->get('driver.filter', new \Moex\CoreBundle\Entity\DriverFilter());
		
		$translator = $this->get('translator');
        $filterForm = $this->createForm(new DriverFilterType($translator), $filter);
        $filterForm->bindRequest($this->getRequest());
        $this->getRequest()->getSession()->set('driver.filter', $filter);

        $order_filter = $this->getRequest()->getSession()->get('order.filter', new \Moex\CoreBundle\Entity\OrderFilter());
		
		$translator = $this->get('translator');
        $order_filterForm = $this->createForm(new OrderFilterType($translator), $order_filter);
        $order_filterForm->bindRequest($this->getRequest());
        $this->getRequest()->getSession()->set('order.filter', $order_filter);
        
        $query = $em->getRepository('MoexCoreBundle:MeMoney')->findByFilterQuery($filter);
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage($this->container->getParameter('moex.pagesize.default'));
        $paginator->setCurrentPage($this->get('request')->query->get('page', 1), false, true);

        $query = $em->getRepository('MoexCoreBundle:MeOrderDriver')->findByFilterQuery($filter, $order_filter);
        $order_paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $order_paginator->setMaxPerPage($this->container->getParameter('moex.pagesize.default'));
        $order_paginator->setCurrentPage($this->get('request')->query->get('page', 1), false, true);

        return array( 'filterForm' => $filterForm->createView(), 
					  'order_filterForm' => $order_filterForm->createView(), 
					  'paginator' => $paginator,
					  'order_paginator' => $order_paginator,
					);
    }

    /**
     * Finds and displays a MeMoney entity.
     *
     * @Route("/{id}/show", name="money_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

		$entity = $em->getRepository('MoexCoreBundle:MeMoney')
							->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MeDrivers entity.');
        }
		
        $filter = $this->getRequest()->getSession()->get('driver.filter', new \Moex\CoreBundle\Entity\DriverFilter());
		
		$translator = $this->get('translator');
        $filterForm = $this->createForm(new DriverFilterType($translator), $filter);
        $filterForm->bindRequest($this->getRequest());
        $this->getRequest()->getSession()->set('driver.filter', $filter);
        
        $query = $em->getRepository('MoexCoreBundle:MeMoney')->findByFilterQuery($filter);
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage($this->container->getParameter('moex.pagesize.default'));
        $paginator->setCurrentPage($this->get('request')->query->get('page', 1), false, true);

        return array( 'entity' => $entity, 'filterForm' => $filterForm->createView(), 'paginator' => $paginator);
    }

    /**
     * Creates a new MeMoney entity.
     *
     * @Route("/{driver_id}/create", name="money_create")
     * @Method("post")
     * @Template("MoexCoreBundle:MeDrivers:charge.html.twig")
     */
    public function createAction($driver_id)
    {
        $entity  = new MeMoney();
        $request = $this->getRequest();
		$translator = $this->get('translator');
        $form    = $this->createForm(new ChargeType($translator), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
        	$user_id = $this->get('security.context')->getToken()->getUser()->ID; 
			$user = $em->getRepository("MoexCoreBundle:MeUsers")->find($user_id);
			$entity->setUser($user);
			$driver = $em->getRepository("MoexCoreBundle:MeDrivers")->find($driver_id);
			$entity->setDriver($driver);
        	$created_at = new \DateTime();
        	$updated_at = new \DateTime();
			$entity->setCreatedAt($created_at);
			$entity->setUpdatedAt($updated_at);
            $em->persist($entity);

			$driver = $em->getRepository("MoexCoreBundle:MeDrivers")->find($driver_id);
			$driver->setMoexMoney($driver->getMoexMoney() - $entity->getAmount());
			$em->persist($driver);
            $em->flush();

            return $this->redirect($this->generateUrl('money_show', array('id' => $entity->getId())));
            
        }

        return array(
            'driver_id' => $driver_id,
            'charge_form'   => $form->createView()
        );
    }

}
