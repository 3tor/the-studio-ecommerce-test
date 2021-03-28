<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CheckoutFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control mb-3')
            ))
            ->add('firstname', TextType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control mb-3')
            ))
            ->add('phone', TextType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control mb-3')
            ))
            ->add('address', TextType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control mb-3')
            ))
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
