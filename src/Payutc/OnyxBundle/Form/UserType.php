<?php

namespace Payutc\OnyxBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
	/**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array('label' => 'Adresse e-mail', 'required' => true))
            ->add('password', 'repeated', array('type' => 'password', 'required' => true, 'first_options' => array('label' => 'Mot de passe'), 'second_options' => array('label' => 'Vérification du mot de passe')))
            ->add('firstname', 'text', array('label' => 'Prénom', 'required' => true))
            ->add('name', 'text', array('label' => 'Nom', 'required' => true))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Payutc\OnyxBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'payutc_onyxbundle_user';
    }
}