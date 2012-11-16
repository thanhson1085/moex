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

        return array(
            'entity'      => $entity,
            );
        return array();
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
			$driver->setMoney($driver->getMoney() - $entity->getAmount());
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
