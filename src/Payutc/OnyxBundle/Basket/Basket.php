<?php

namespace Payutc\OnyxBundle\Basket;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;

use Payutc\OnyxBundle\Entity\Ticket;
use Payutc\OnyxBundle\Basket\Exception\FullEventException;
use Payutc\OnyxBundle\Basket\Exception\TooMuchPlacesForUserException;
use Payutc\OnyxBundle\Basket\Exception\FullPriceException;
use Payutc\OnyxBundle\Basket\Exception\TooMuchPlacesOfPriceForUserException;
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
     * Add a ticket in the basket
     *
     * @param Ticket $ticket
     * @return Basket
     */
    public function add(Ticket $ticket)
    {
        if (!$this->collection->contains($ticket)) {
            $this->collection->add($ticket);
        }

        return $this;
    }

    /**
     * Remove a ticket from the basket
     *
     * @param Ticket $ticket
     * @return Basket
     */
    public function remove(Ticket $ticket)
    {
        $this->collection->removeElement($ticket);

        return $this;
    }

    /**
     * Store the basket content in session
     *
     * @return Basket
     */
    public function store()
    {
        $this->session->set('onyxBasketCollection', $this->collection);

        return $this;
    }

    /**
     * Validate and pay all items from the basket
     *
     * @param Ticket $ticket
     * @return Basket
     */
    public function validate()
    {
        echo '<pre>';
        $this->collection->forAll(function ($key, $ticket) {
            var_dump($key, $ticket);
            // Check the event global capacity
            $eventCapacity = $ticket->getPrice()->getEvent()->getCapacity();
            $eventPaidPlaces = $this->em->getRepository('PayutcOnyxBundle:Ticket')->countAllPaidForEvent($ticket->getPrice()->getEvent());
            
            if ($eventCapacity <= $eventPaidPlaces) {
                throw new FullEventException();
            }
            
            // Check the places allowed for the user
            $allowedPlacesForUser = $ticket->getPrice()->getEvent()->getMaxPlacesForUser();
            $eventPaidPlacesByUser = $this->em->getRepository('PayutcOnyxBundle:Ticket')->countAllPaidForEventAndBuyer($ticket->getPrice()->getEvent(), $this->getUser());
            
            if ($allowedPlacesForUser <= $eventPaidPlacesByUser) {
                throw new TooMuchPlacesForUserException();
            }
            
            // Check the price capacity
            $priceCapacity = $ticket->getPrice()->getCapacity();
            $pricePaidPlaces = $this->em->getRepository('PayutcOnyxBundle:Ticket')->countAllPaidForEventAndPrice($ticket->getPrice()->getEvent(), $ticket->getPrice());
            
            if ($priceCapacity <= $pricePaidPlaces) {
                throw new FullPriceException();
            }
            
            // Check the places allowed by price for the user
            $allowedPlacesOfPriceForUser = $ticket->getPrice()->getMaxPlacesForUser();
            $pricePaidPlaces = $this->em->getRepository('PayutcOnyxBundle:Ticket')->countAllPaidForEventAndPriceAndBuyer($ticket->getPrice()->getEvent(), $ticket->getPrice(), $this->getUser());
            
            if ($priceCapacity <= $pricePaidPlaces) {
                throw new TooMuchPlacesOfPriceForUserException();
            }
            
            // Check the price time expiration
            $priceEnd = $ticket->getPrice()->getEndAt();
            $now = new \DateTime();
            
            if ($now < $priceEnd) {
                throw new ExpiredPriceException();
            }

            // Otherwise, everything's well : validate the ticket and store it.
            $ticket->validate();
            $this->em->persist($ticket);
            $this->em->flush();
        });
        exit(var_dump('</pre>'));

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
}