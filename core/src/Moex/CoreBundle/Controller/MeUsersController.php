<?php
namespace Moex\CoreBundle\Controller;

use Symfony\Component\Request\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Moex\CoreBundle\Entity\MeUsers;
use Moex\CoreBundle\Form\UserFilterType;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
/**
 * MeUsers controller.
 *
 * @Route("/user")
 */
class MeUsersController extends Controller
{
    /**
     * Lists all MeUsers entities.
     *
     * @Route("/", name="user")
     * @Template()
     */
    public function indexAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $filter = $this->getRequest()->getSession()->get('user.filter', new \Moex\CoreBundle\Entity\UserFilter());

		$translator = $this->get('translator');
        $filterForm = $this->createForm(new UserFilterType($translator), $filter);
        $filterForm->bindRequest($this->getRequest());
        $this->getRequest()->getSession()->set('user.filter', $filter);
        
        $query = $em->getRepository('MoexCoreBundle:MeUsers')->findByFilterQuery($filter);
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage($this->container->getParameter('moex.pagesize.default'));
        $paginator->setCurrentPage($this->get('request')->query->get('page', 1), false, true);
		
        return array('filterForm' => $filterForm->createView(), 'paginator' => $paginator);
	}

}
