<?php

namespace Payutc\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserGroupType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isHidden', 'checkbox', array('label' => 'Masqué ou non ?', 'required' => false))
            ->add('title', 'text', array('label' => 'Nom du groupe', 'required' => true))
            ->add('informations', 'textarea', array('label' => 'Informations for User:getMyGroups', 'required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Payutc\OnyxBundle\Entity\UserGroup'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'payutc_adminbundle_usergroup';
    }
}
