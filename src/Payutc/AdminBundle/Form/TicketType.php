<?php

namespace Payutc\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TicketType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('barcode', 'text', array('label' => 'Code bar', 'required' => false))
            ->add('firstname', 'text', array('label' => 'Prénom', 'required' => false))
            ->add('lastname', 'text', array('label' => 'Nom', 'required' => false))
            ->add('paidAt', 'datetime', array('label' => 'Date de paiement', 'required' => false, 'input' => 'datetime', 'widget' => 'single_text', 'format' => 'yyyy-MM-dd HH:mm:ss', 'attr' => array('data-format' => 'YYYY-MM-DD hh:mm:ss')))
            ->add('paidPrice', 'integer', array('label' => 'Prix payé', 'required' => true))
            ->add('user', 'entity', array('label' => 'Acheteur', 'required' => true, 'class' => 'PayutcOnyxBundle:User'))
            ->add('price', 'entity', array('label' => 'Tarif', 'required' => true, 'class' => 'PayutcOnyxBundle:Price'))
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
        return 'payutc_adminbundle_ticket';
    }
}
