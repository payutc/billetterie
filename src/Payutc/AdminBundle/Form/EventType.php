<?php

namespace Payutc\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isHidden', 'checkbox', array('label' => 'Actif ?', 'required' => false))
            ->add('title', 'text', array('label' => 'Titre', 'required' => true))
            ->add('dueAt', 'datetime', array('label' => 'Date', 'required' => false, 'input' => 'datetime', 'widget' => 'single_text', 'format' => 'yyyy-MM-dd HH:mm:ss', 'attr' => array('data-format' => 'YYYY-MM-DD hh:mm:ss')))
            ->add('thumbnailFile', 'file', array('label' => 'Miniature ?', 'required' => false))
            ->add('headerPictureFile', 'file', array('label' => 'Image d\'en-tête', 'required' => false))
            ->add('content', 'textarea', array('label' => 'Contenu', 'required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Payutc\OnyxBundle\Entity\Event'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'payutc_adminbundle_event';
    }
}