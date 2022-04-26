<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', TextType::class, ['label' => 'Login : '])
            ->add('firstName', TextType::class, ['label' => 'Prénom : '])
            ->add('lastName', TextType::class, ['label' => 'Nom : '])
            ->add('dateOfBirth', DateType::class, ['label' => 'Date de naissance : ', 'years' => range(date('Y'), 1920),])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
