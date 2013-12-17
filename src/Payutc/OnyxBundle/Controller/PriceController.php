<?php

namespace Payutc\OnyxBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Payutc\OnyxBundle\Entity\Price;
use Payutc\OnyxBundle\Form\PriceType;

/**
 * Price controller.
 *
 */
class PriceController extends Controller
{

    /**
     * Lists all Price entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PayutcOnyxBundle:Price')->findAll();

        return $this->render('PayutcOnyxBundle:Price:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Price entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Price();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('prices_show', array('id' => $entity->getId())));
        }

        return $this->render('PayutcOnyxBundle:Price:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Price entity.
    *
    * @param Price $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Price $entity)
    {
        $form = $this->createForm(new PriceType(), $entity, array(
            'action' => $this->generateUrl('prices_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Price entity.
     *
     */
    public function newAction()
    {
        $entity = new Price();
        $form   = $this->createCreateForm($entity);

        return $this->render('PayutcOnyxBundle:Price:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Price entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PayutcOnyxBundle:Price')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Price entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PayutcOnyxBundle:Price:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Price entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PayutcOnyxBundle:Price')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Price entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PayutcOnyxBundle:Price:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Price entity.
    *
    * @param Price $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Price $entity)
    {
        $form = $this->createForm(new PriceType(), $entity, array(
            'action' => $this->generateUrl('prices_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Price entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PayutcOnyxBundle:Price')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Price entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('prices_edit', array('id' => $id)));
        }

        return $this->render('PayutcOnyxBundle:Price:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Price entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PayutcOnyxBundle:Price')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Price entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('prices'));
    }

    /**
     * Creates a form to delete a Price entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('prices_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
