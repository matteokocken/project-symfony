<?php

namespace App\Form;

use App\Entity\UserSecure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', TextType::class, ['label' => 'Login : '])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe : '])
            ->add('firstName', TextType::class, ['label' => 'Prénom : '])
            ->add('lastName', TextType::class, ['label' => 'nom : '])
            ->add('dateOfBirth', DateType::class, ['label' => 'Date de naissance : ', 'years' => range(date('Y'), 1920)])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserSecure::class,
        ]);
    }
}
