<?php

namespace Payutc\OnyxBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Payutc\OnyxBundle\Entity\PriceRepository;

class TicketType extends AbstractType
{
    private $event;
    private $user;

    public function __construct($event, $user)
    {
        $this->event = $event;
        $this->user = $user;
    }

	/**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $event = $this->event;
        // TODO : get User UserGroups from anywhere...
        $userGroups = array();

        $builder
            ->add('firstname', 'text', array('label' => 'Prénom', 'required' => true))
            ->add('lastname', 'text', array('label' => 'Nom de famille', 'required' => true))
            ->add('price', 'entity', array('label' => 'Tarif', 'required' => true, 'class' => 'PayutcOnyxBundle:Price', 'query_builder' => function (PriceRepository $er) use ($event, $userGroups) {
                return $er->getQBOfAvailableForEventAndUserGroups($event, $userGroups);
            }))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Payutc\OnyxBundle\Entity\Ticket'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'payutc_onyxbundle_ticket';
    }
}
