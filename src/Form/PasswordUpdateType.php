<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('oldPassword', PasswordType::class, $this->getConfiguration(
            'Votre ancien mot de passe', 'Saississez votre ancien mot de passe'
        ))
            ->add('newPassword', PasswordType::class, $this->getConfiguration(
                'Votre nouveau mot de passe', 'Saissisez votre nouveau mot de passe'
            ))
            ->add('confirmPassword', PasswordType::class, $this->getConfiguration(
                'Confirmation de votre nouveau mot de passe','Confirmez votre mot de passe'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
