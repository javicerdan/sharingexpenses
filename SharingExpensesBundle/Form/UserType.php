<?php

namespace Javicode\SharingExpensesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text')
            ->add(
                'password',
                'repeated',
                array(
                    'first_name' => 'password',
                    'second_name' => 'confirm_password',
                    'type' => 'password',
                )
            )
            ->add('email', 'email')
            ->add('save', 'submit');
        //TODO: Pic upload
    }

    public function getName()
    {
        return 'register';
    }
}
