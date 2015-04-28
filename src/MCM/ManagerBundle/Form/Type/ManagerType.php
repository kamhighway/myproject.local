<?php

namespace MCM\ManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ManagerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('login','text')
                ->add('firstname', 'text')
                ->add('lastname','text')
                ->add('managerkey','text')
                ->add('save','submit')
                ->getForm();
    }

    public function getName()
    {
        return 'manager';
    }
}