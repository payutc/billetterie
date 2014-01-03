<?php

namespace Payutc\AdminBundle\Controller\Crud;

use Symfony\Component\HttpFoundation\Request;

use Payutc\OnyxBundle\Entity\Price;
use Payutc\AdminBundle\Form\PriceType;

/**
 * Price controller.
 *
 */
class PriceController extends CrudController
{
    /**
     * Lists all Price entities.
     *
     */
    public function indexAction()
    {
        return $this->listEntities('Price', 'PayutcOnyxBundle', 'PayutcAdminBundle');
    }

    /**
     * Displays a form to create a new Price entity.
     *
     */
    public function newAction()
    {
        return $this->renderCreationForm(new Price(), new PriceType(), 'Price', 'PayutcAdminBundle');
    }

    /**
     * Creates a new Price entity.
     *
     */
    public function createAction(Request $request)
    {
        return $this->createEntity($request, new Price(), new PriceType(), 'Price', 'PayutcAdminBundle', 'payutc_admin_price_show');
    }

    /**
     * Finds and displays a Price entity.
     *
     */
    public function showAction($id)
    {
        return $this->showEntity($id, 'Price', 'PayutcOnyxBundle', 'PayutcAdminBundle');
    }

    /**
     * Enable any Price entity to be displayed in front-end.
     *
     */
    public function activateAction($id)
    {
        return $this->activateEntity($id, 'Price', 'PayutcOnyxBundle', 'payutc_admin_prices');
    }

    /**
     * Disable any Price entity to be displayed in front-end.
     *
     */
    public function unactivateAction($id)
    {
        return $this->unactivateEntity($id, 'Price', 'PayutcOnyxBundle', 'payutc_admin_prices');
    }

    /**
     * Displays a form to edit an existing Price entity.
     *
     */
    public function editAction($id)
    {
        return $this->renderEditForm($id, new PriceType(), 'Price', 'PayutcOnyxBundle', 'PayutcAdminBundle');
    }

    /**
     * Edits an existing Price entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        return $this->updateEntity($request, $id, new PriceType(), 'Price', 'PayutcOnyxBundle', 'PayutcAdminBundle', 'payutc_admin_prices');
    }

    /**
     * Removes a Price entity.
     *
     */
    public function removeAction(Request $request, $id)
    {
        return $this->removeEntity($request, $id, 'Price', 'PayutcOnyxBundle', 'payutc_admin_prices');
    }
}