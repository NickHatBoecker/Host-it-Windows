<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VirtualHostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('serverName', 'text', array(
                'label' => 'Server Name',
            ))
            ->add('documentRoot', 'text', array(
                'label' => 'Document Root',
            ))
        ;
    }

    public function getName()
    {
        return 'virtual_host';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\VirtualHost',
        ));
    }
}
