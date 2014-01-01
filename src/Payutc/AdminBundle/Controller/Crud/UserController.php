<?php

namespace Payutc\AdminBundle\Controller\Crud;

use Symfony\Component\HttpFoundation\Request;

use Payutc\OnyxBundle\Entity\User;
use Payutc\AdminBundle\Form\UserType;

/**
 * User controller.
 *
 */
class UserController extends CrudController
{
    /**
     * Lists all User entities.
     *
     */
    public function indexAction()
    {
        return $this->listEntities('User', 'PayutcOnyxBundle', 'PayutcAdminBundle');
    }

    /**
     * Displays a form to create a new User entity.
     *
     */
    public function newAction()
    {
        return $this->renderCreationForm(new User(), new UserType(), 'User', 'PayutcAdminBundle');
    }

    /**
     * Creates a new User entity.
     *
     */
    public function createAction(Request $request)
    {
        return $this->createEntity($request, new User(), new UserType(), 'User', 'PayutcAdminBundle', 'payutc_admin_user_show');
    }

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction($id)
    {
        return $this->showEntity($id, 'User', 'PayutcOnyxBundle', 'PayutcAdminBundle');
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction($id)
    {
        return $this->renderEditForm($id, new UserType(), 'User', 'PayutcOnyxBundle', 'PayutcAdminBundle');
    }

    /**
     * Edits an existing User entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        return $this->updateEntity($request, $id, new UserType(), 'User', 'PayutcOnyxBundle', 'PayutcAdminBundle', 'payutc_admin_users');
    }
}