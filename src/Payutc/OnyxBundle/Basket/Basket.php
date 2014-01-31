<?php

namespace Payutc\OnyxBundle\Basket;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;

use Payutc\OnyxBundle\Entity\Ticket;
use Payutc\OnyxBundle\Basket\Exception\BasketException;
use Payutc\OnyxBundle\Basket\Exception\FullEventException;
use Payutc\OnyxBundle\Basket\Exception\FullEventForUserException;
use Payutc\OnyxBundle\Basket\Exception\FullPriceException;
use Payutc\OnyxBundle\Basket\Exception\FullPriceForUserException;
use Payutc\OnyxBundle\Basket\Exception\ExpiredPriceException;

class Basket
{
    private $user;
    private $em;
    protected $session;
    protected $collection;

    /**
     * Magic constructor
     *
     * @param Session $session
     * @return Basket
     */
    public function __construct(Session $session, SecurityContext $context, EntityManager $em)
    {
        // Minimal ROLE to buy some places
        if (!$context->isGranted('ROLE_USER')) {
            return;
        }

        $this->session = $session;
        $this->em = $em;
        $this->user = $context->getToken()->getUser();

        $this->collection = $this->session->get('onyxBasketCollection');

        if (!$this->collection) {
            $this->collection = new ArrayCollection();
        }

        return $this;
    }

    /**
     * Add a ticket in the basket if it is available
     *
     * @param Ticket $ticket
     * @return boolean
     */
    public function add(Ticket $ticket)
    {
        $ticketIsAdded = false;

        if (!$this->collection->contains($ticket)) {
            try {
                if ($this->isTicketAvailable($ticket)) {
                    $this->collection->add($ticket);
                    $this->store();
                    $ticketIsAdded = true;
                }
            }
            catch (BasketException $e) {
                $this->getSession()->getFlashBag()->add('warning', $e->getMessage());
            }
        }

        return $ticketIsAdded;
    }

    /**
     * Remove a ticket from the basket if it is contained.
     *
     * @param Ticket $ticket
     * @return Basket
     */
    public function remove(Ticket $ticket)
    {
        $this->collection->removeElement($ticket);
        $this->store();

        return $this;
    }

    /**
     * Refresh the basket item in the session.
     *
     * @return Basket
     */
    public function refresh(Ticket $ticket)
    {
        $this->collection->forAll(function ($key, $item) use ($ticket) {
            if ($item->getId() === $ticket->getId()) {
                $this->collection->set($key, $ticket);
            }
        });

        return $this->store();
    }

    /**
     * Store the basket content in session
     *
     * @return Basket
     */
    protected function store()
    {
        $this->session->set('onyxBasketCollection', $this->collection);

        return $this;
    }

    /**
     * Count the basket items
     *
     * @return Basket
     */
    public function count()
    {
        return $this->getCollection()->count();
    }

    /**
     * Validate and pay all items from the basket
     *
     * @param Ticket $ticket
     * @return Basket
     */
    public function validate()
    {
        $this->collection->forAll(function ($key, $ticket) {
            $availability = false;

            try {
                $availability = $this->isTicketAvailable($ticket);
            }
            catch (BasketException $e) {
                $this->getSession()->getFlashBag()->add('warning', $e->getMessage());
                $this->remove($ticket);
            }

            if ($availability) {                
                // TODO: Paiement ???

                $this->getEntityManager()->persist($ticket);
            }
        });

        $this->getEntityManager()->flush();

        return $this;
    }

    /**
     * Unvalidate the basket and remove all items
     *
     * @return Basket
     */
    public function unvalidate()
    {
        $this->collection->clear();

        return $this;
    }

    /**
     * Check the ticket availability
     *
     * @param Ticket $ticket
     * @return boolean
     */
    protected function isTicketAvailable(Ticket $ticket)
    {
        $availability = true;

        // Check the event global capacity
        $eventCapacity = $ticket->getPrice()->getEvent()->getCapacity();
        $eventPaidPlaces = $this->getEntityManager()->getRepository('PayutcOnyxBundle:Ticket')->countAllPaidForEvent($ticket->getPrice()->getEvent());
        
        if ($eventCapacity <= $eventPaidPlaces) {
            $availability = false;
            throw new FullEventException();
        }
        
        // Check the places allowed for the user
        $allowedPlacesForUser = $ticket->getPrice()->getEvent()->getMaxPlacesForUser();
        $eventPaidPlacesByUser = $this->getEntityManager()->getRepository('PayutcOnyxBundle:Ticket')->countAllPaidForEventAndBuyer($ticket->getPrice()->getEvent(), $this->getUser());
        
        if ($allowedPlacesForUser <= $eventPaidPlacesByUser) {
            $availability = false;
            throw new FullEventForUserException();
        }
        
        // Check the price capacity
        $priceCapacity = $ticket->getPrice()->getCapacity();
        $pricePaidPlaces = $this->getEntityManager()->getRepository('PayutcOnyxBundle:Ticket')->countAllPaidForEventAndPrice($ticket->getPrice()->getEvent(), $ticket->getPrice());
        
        if ($priceCapacity <= $pricePaidPlaces) {
            $availability = false;
            throw new FullPriceException();
        }
        
        // Check the places allowed by price for the user
        $allowedPlacesOfPriceForUser = $ticket->getPrice()->getMaxPlacesForUser();
        $pricePaidPlaces = $this->getEntityManager()->getRepository('PayutcOnyxBundle:Ticket')->countAllPaidForEventAndPriceAndBuyer($ticket->getPrice()->getEvent(), $ticket->getPrice(), $this->getUser());
        
        if ($priceCapacity <= $pricePaidPlaces) {
            $availability = false;
            throw new FullPriceForUserException();
        }
        
        // Check the price time expiration
        $priceEnd = $ticket->getPrice()->getEndAt();
        $now = new \DateTime();
        
        if ($now < $priceEnd) {
            $availability = false;
            throw new ExpiredPriceException();
        }

        return $availability;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get session
     *
     * @return User
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Get entity manager
     *
     * @return Entity Manager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * Get collection
     *
     * @return Collcetion
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Get collection
     *
     * @return Collcetion
     */
    public function getAllItems()
    {
        $items = array();
        $this->collection->forAll(function ($key, $item) {
            $items[] = $this->getEntityManager()->getRepository(get_class($item))->find($item->getId());
        });
        return $items;
    }
}