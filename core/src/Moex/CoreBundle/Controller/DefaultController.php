<?php

namespace Moex\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Request\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Moex\CoreBundle\Entity\MeOrders;
use Moex\CoreBundle\Form\MeOrdersType;
use Moex\CoreBundle\Form\OrderFilterType;
use Moex\CoreBundle\Entity\MeDrivers;
use Moex\CoreBundle\Form\MeDriversType;
use Moex\CoreBundle\Form\DriverFilterType;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;


class DefaultController extends Controller
{
    /**
     * @Route("/index", name="dashboard")
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
        
        $filter_driver = $this->getRequest()->getSession()->remove('driver.filter');
        $filter_driver = $this->getRequest()->getSession()->get('driver.filter', new \Moex\CoreBundle\Entity\DriverFilter());
		
        $filterDriverForm = $this->createForm(new DriverFilterType($translator), $filter_driver);
        $filterDriverForm->bindRequest($this->getRequest());
        $this->getRequest()->getSession()->set('driver.filter', $filter_driver);
        
        $query = $em->getRepository('MoexCoreBundle:MeDrivers')->findByFilterQuery($filter_driver);
        $paginator_driver = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator_driver->setMaxPerPage($this->container->getParameter('moex.pagesize.default'));
        $paginator_driver->setCurrentPage($this->get('request')->query->get('page', 1), false, true);

        return array(
					'filterForm' => $filterForm->createView(), 
					'filterDriverForm' => $filterDriverForm->createView(), 
					'paginator' => $paginator,
					'paginator_driver' => $paginator_driver
				);
    }
	/**
	* @Template()
	*/
	public function unauthorizedAction()
	{
		return array();
	}
}
