<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;


class AnnonceType extends AbstractType
{
    /**
     * Permet d'avoir le label et le placeholder pour les formulaires
     *
     * @param $label le label du champ du formulaire
     * @param $placeholder le placeholder du champ du formulaire
     * @return array
     */
    private function getConfiguration($label, $placeholder){
        return [
            'label'=>$label,
            'attr'=>[
                'placeholder'=>$placeholder
                ]
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration('Titre',
                 'Titre de l\'annonce'))
            ->add('slug', TextType::class, $this->getConfiguration('Chaine URL',
                'Adresse web (automatique)'))
            ->add('coverImage', UrlType::class, $this->getConfiguration('URl de l\'image principale',
                'Donnez une URL qui fait réver'))
            ->add('introduction', TextType::class, $this->getConfiguration('Introduction',
                'Donnez une description globale de l\'annonce'))
            ->add('content', TextareaType::class, $this->getConfiguration( 'Description détaillée',
                'Tapez une description qui donne vraiment envie de venir chez vous !!'))
            ->add('rooms', IntegerType::class, $this->getConfiguration('Nombre de chambres',
                'Le nombre de champbres disponibles'))
            ->add('price', MoneyType::class, $this->getConfiguration('Prix par nuit',
                'Indiquez le prix par nuit'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
