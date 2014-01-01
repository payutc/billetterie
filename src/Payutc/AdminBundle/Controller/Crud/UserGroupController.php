<?php

namespace Payutc\AdminBundle\Controller\Crud;

use Symfony\Component\HttpFoundation\Request;

use Payutc\OnyxBundle\Entity\UserGroup;
use Payutc\AdminBundle\Form\UserGroupType;

/**
 * UserGroup controller.
 *
 */
class UserGroupController extends CrudController
{
    /**
     * Lists all UserGroup entities.
     *
     */
    public function indexAction()
    {
        return $this->listEntities('UserGroup', 'PayutcOnyxBundle', 'PayutcAdminBundle');
    }

    /**
     * Displays a form to create a new UserGroup entity.
     *
     */
    public function newAction()
    {
        return $this->renderCreationForm(new UserGroup(), new UserGroupType(), 'UserGroup', 'PayutcAdminBundle');
    }

    /**
     * Creates a new UserGroup entity.
     *
     */
    public function createAction(Request $request)
    {
        return $this->createEntity($request, new UserGroup(), new UserGroupType(), 'UserGroup', 'PayutcAdminBundle', 'payutc_admin_usergroup_show');
    }

    /**
     * Finds and displays a UserGroup entity.
     *
     */
    public function showAction($id)
    {
        return $this->showEntity($id, 'UserGroup', 'PayutcOnyxBundle', 'PayutcAdminBundle');
    }

    /**
     * Enable any UserGroup entity to be displayed in front-end.
     *
     */
    public function activateAction($id)
    {
        return $this->activateEntity($id, 'UserGroup', 'PayutcOnyxBundle', 'payutc_admin_usergroups');
    }

    /**
     * Disable any UserGroup entity to be displayed in front-end.
     *
     */
    public function unactivateAction($id)
    {
        return $this->unactivateEntity($id, 'UserGroup', 'PayutcOnyxBundle', 'payutc_admin_usergroups');
    }

    /**
     * Displays a form to edit an existing UserGroup entity.
     *
     */
    public function editAction($id)
    {
        return $this->renderEditForm($id, new UserGroupType(), 'UserGroup', 'PayutcOnyxBundle', 'PayutcAdminBundle');
    }

    /**
     * Edits an existing UserGroup entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        return $this->updateEntity($request, $id, new UserGroupType(), 'UserGroup', 'PayutcOnyxBundle', 'PayutcAdminBundle', 'payutc_admin_usergroups');
    }

    /**
     * Removes a UserGroup entity.
     *
     */
    public function removeAction(Request $request, $id)
    {
        return $this->removeEntity($request, $id, 'UserGroup', 'PayutcOnyxBundle', 'payutc_admin_usergroups');
    }
}