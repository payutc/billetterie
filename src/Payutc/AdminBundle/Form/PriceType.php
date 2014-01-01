<?php

namespace Payutc\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PriceType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isHidden', 'checkbox', array('label' => 'Masqué ou non ?', 'required' => false))
            ->add('startAt', 'datetime', array('label' => 'Début de validité du tarif', 'required' => false, 'input' => 'datetime', 'widget' => 'single_text', 'format' => 'yyyy-MM-dd HH:mm:ss', 'attr' => array('data-format' => 'YYYY-MM-DD hh:mm:ss')))
            ->add('endAt', 'datetime', array('label' => 'Fin de validité du tarif', 'required' => false, 'input' => 'datetime', 'widget' => 'single_text', 'format' => 'yyyy-MM-dd HH:mm:ss', 'attr' => array('data-format' => 'YYYY-MM-DD hh:mm:ss')))
            ->add('title', 'text', array('label' => 'Titre', 'required' => true))
            ->add('description', 'textarea', array('label' => 'Description', 'required' => true))
            ->add('price', 'integer', array('label' => 'Prix', 'required' => true))
            ->add('isNominative', 'checkbox', array('label' => 'Tarif nominatif ou non ?', 'required' => true))
            ->add('capacity', 'integer', array('label' => 'Nombre de places pour le tarif', 'required' => true))
            ->add('maxPlacesForUser', 'integer', array('label' => 'Nombre de places - par acheteur - pour le tarif', 'required' => true))
            ->add('isUniqueForUser', 'checkbox', array('label' => 'Unique ou non par acheteur ?', 'required' => true))
            ->add('payutcId', 'integer', array('label' => 'PayutcId', 'required' => true))
            ->add('userGroups', 'entity', array('label' => 'Groupes bénéficiant de ce tarif', 'required' => false, 'multiple' => true, 'expanded' => false, 'class' => 'PayutcOnyxBundle:UserGroup'))
            ->add('event', 'entity', array('label' => 'Evénement associé', 'required' => true, 'class' => 'PayutcOnyxBundle:Event'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Payutc\OnyxBundle\Entity\Price'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'payutc_adminbundle_price';
    }
}
