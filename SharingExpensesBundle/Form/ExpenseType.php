<?php

namespace Javicode\SharingExpensesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ExpenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text')
            ->add('price', 'money', array('currency' => false))
            ->add('description', 'textarea', array('required' => false))
            ->add('save', 'submit');
        //TODO: Pic upload
    }

    public function getName()
    {
        return 'register';
    }
}
