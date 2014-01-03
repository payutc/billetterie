<?php

namespace Payutc\AdminBundle\Controller\Crud;

use Symfony\Component\HttpFoundation\Request;

use Payutc\OnyxBundle\Entity\Ticket;
use Payutc\AdminBundle\Form\TicketType;

/**
 * Ticket controller.
 *
 */
class TicketController extends CrudController
{
    /**
     * Lists all Ticket entities.
     *
     */
    public function indexAction()
    {
        return $this->listEntities('Ticket', 'PayutcOnyxBundle', 'PayutcAdminBundle');
    }

    /**
     * Displays a form to create a new Ticket entity.
     *
     */
    public function newAction()
    {
        return $this->renderCreationForm(new Ticket(), new TicketType(), 'Ticket', 'PayutcAdminBundle');
    }

    /**
     * Creates a new Ticket entity.
     *
     */
    public function createAction(Request $request)
    {
        return $this->createEntity($request, new Ticket(), new TicketType(), 'Ticket', 'PayutcAdminBundle', 'payutc_admin_ticket_show');
    }

    /**
     * Finds and displays a Ticket entity.
     *
     */
    public function showAction($id)
    {
        return $this->showEntity($id, 'Ticket', 'PayutcOnyxBundle', 'PayutcAdminBundle');
    }

    /**
     * Enable any Ticket entity to be displayed in front-end.
     *
     */
    public function activateAction($id)
    {
        return $this->activateEntity($id, 'Ticket', 'PayutcOnyxBundle', 'payutc_admin_tickets');
    }

    /**
     * Disable any Ticket entity to be displayed in front-end.
     *
     */
    public function unactivateAction($id)
    {
        return $this->unactivateEntity($id, 'Ticket', 'PayutcOnyxBundle', 'payutc_admin_tickets');
    }

    /**
     * Displays a form to edit an existing Ticket entity.
     *
     */
    public function editAction($id)
    {
        return $this->renderEditForm($id, new TicketType(), 'Ticket', 'PayutcOnyxBundle', 'PayutcAdminBundle');
    }

    /**
     * Edits an existing Ticket entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        return $this->updateEntity($request, $id, new TicketType(), 'Ticket', 'PayutcOnyxBundle', 'PayutcAdminBundle', 'payutc_admin_tickets');
    }

    /**
     * Removes a Ticket entity.
     *
     */
    public function removeAction(Request $request, $id)
    {
        return $this->removeEntity($request, $id, 'Ticket', 'PayutcOnyxBundle', 'payutc_admin_tickets');
    }
}