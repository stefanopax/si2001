<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\Skill;
use App\Entity\User;
use App\Entity\Status;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('name')
            ->add('surname')
            ->add('birthday')
            ->add('country')
            ->add('link'/*,FileType::class*/)
            ->add('status', EntityType::class, array(
                    // looks for choices from this entity
                    'class' => Status::class,
                    'choice_label' => 'name'
                )
            )
            ->add('role_ids', EntityType::class, array(
                    // looks for choices from this entity
                    'class' => Role::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true,
                )
            )
            ->add('skill_ids', EntityType::class, array(
                    // looks for choices from this entity
                    'class' => Skill::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Skills'
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
