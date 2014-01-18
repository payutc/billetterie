<?php

namespace Payutc\AdminBundle\Controller\Crud;

use Symfony\Component\HttpFoundation\Request;

use Payutc\OnyxBundle\Entity\Event;
use Payutc\AdminBundle\Form\EventType;

/**
 * Event controller.
 *
 */
class EventController extends CrudController
{
    /**
     * Lists all Event entities.
     *
     */
    public function indexAction()
    {
        return $this->listEntities('Event', 'PayutcOnyxBundle', 'PayutcAdminBundle');
    }

    /**
     * Displays a form to create a new Event entity.
     *
     */
    public function newAction()
    {
        return $this->renderCreationForm(new Event(), new EventType(), 'Event', 'PayutcAdminBundle');
    }

    /**
     * Creates a new Event entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Event();

        return $this->createEntity($request, $entity, new EventType(), 'Event', 'PayutcAdminBundle', 'payutc_admin_event_show', array(), function ($entity) {
            if ($entity->getThumbnail()) {
                $this->get('image.handling')
                    ->open($entity->getThumbnailAbsolutePath())
                    ->scaleResize(300, 300)
                    ->save($entity->getThumbnailAbsolutePath())
                ;
            }
            if ($entity->getHeaderPicture()) {
                $this->get('image.handling')
                    ->open($entity->getHeaderPictureAbsolutePath())
                    ->scaleResize(825, 200)
                    ->save($entity->getHeaderPictureAbsolutePath())
                ;
            }
        });
    }

    /**
     * Finds and displays a Event entity.
     *
     */
    public function showAction($id)
    {
        return $this->showEntity($id, 'Event', 'PayutcOnyxBundle', 'PayutcAdminBundle');
    }

    /**
     * Enable any Event entity to be displayed in front-end.
     *
     */
    public function activateAction($id)
    {
        return $this->activateEntity($id, 'Event', 'PayutcOnyxBundle', 'payutc_admin_events');
    }

    /**
     * Disable any Event entity to be displayed in front-end.
     *
     */
    public function unactivateAction($id)
    {
        return $this->unactivateEntity($id, 'Event', 'PayutcOnyxBundle', 'payutc_admin_events');
    }

    /**
     * Displays a form to edit an existing Event entity.
     *
     */
    public function editAction($id)
    {
        return $this->renderEditForm($id, new EventType(), 'Event', 'PayutcOnyxBundle', 'PayutcAdminBundle');
    }

    /**
     * Edits an existing Event entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        return $this->updateEntity($request, $id, new EventType(), 'Event', 'PayutcOnyxBundle', 'PayutcAdminBundle', 'payutc_admin_events', array(), function ($entity) {
            if ($entity->getThumbnail()) {
                $this->get('image.handling')
                    ->open($entity->getThumbnailAbsolutePath())
                    ->scaleResize(300, 300)
                    ->save($entity->getThumbnailAbsolutePath())
                ;
            }
            if ($entity->getHeaderPicture()) {
                $this->get('image.handling')
                    ->open($entity->getHeaderPictureAbsolutePath())
                    ->scaleResize(825, 200)
                    ->save($entity->getHeaderPictureAbsolutePath())
                ;
            }
        });
    }

    /**
     * Removes a Event entity.
     *
     */
    public function removeAction(Request $request, $id)
    {
        return $this->removeEntity($request, $id, 'Event', 'PayutcOnyxBundle', 'payutc_admin_events');
    }
}