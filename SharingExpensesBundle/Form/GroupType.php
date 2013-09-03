<?php

namespace Javicode\SharingExpensesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;

class GroupType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options Emails to be listed for this Group
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text')
            ->add(
                'users',
                'collection',
                array(
                    'type' => 'user_email',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'options' => array('required' => true, 'attr' => array('class' => 'email-box'))
                )
            )
            ->add('save', 'submit');
        //TODO: Validate users
    }

    public function addUsersFromEmailsListener(FormEvent $event)
    {

    }

    public function getName()
    {
        return 'modify_group';
    }
}