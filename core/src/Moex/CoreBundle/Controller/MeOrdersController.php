<?php

namespace Moex\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Moex\CoreBundle\Entity\MeOrders;
use Moex\CoreBundle\Form\MeOrdersType;

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

        $entities = $em->getRepository('MoexCoreBundle:MeOrders')->findAll();

        return array('entities' => $entities);
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

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
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
        $form   = $this->createForm(new MeOrdersType(), $entity);

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
        $form    = $this->createForm(new MeOrdersType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
        	$created_at = new \DateTime();
        	$updated_at = new \DateTime();
        	$user_id = get_current_user_id();
			$entity->setCreatedAt($created_at);
			$entity->setUpdatedAt($updated_at);
			$entity->setUserId($user_id);
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

        $editForm = $this->createForm(new MeOrdersType(), $entity);
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

        $editForm   = $this->createForm(new MeOrdersType(), $entity);
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
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('MoexCoreBundle:MeOrders')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MeOrders entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('order'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
