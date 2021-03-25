<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control mb-3')
            ))
            ->add('firstname', TextType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control mb-3')
            ))
            ->add('lastname', TextType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control mb-3')
            ))
            ->add('phone', TelType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control mb-3')
            ))
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'attr' => array('class' => 'form-control mb-3'),
                'multiple' => false,
                'expanded' => false,
                'choices'  => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_SUPERADMIN',
                ],
            ])
            ->add('address', TextType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control mb-3')
            ));

        // Data transformer
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
