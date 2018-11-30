<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30/11/2018
 * Time: 15:03
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType
{
    /**
     * Permet d'avoir le label et le placeholder pour les formulaires
     *
     * @param $label le label du champ du formulaire
     * @param $placeholder le placeholder du champ du formulaire
     * @param $options les options à ajouter
     *
     * @return array
     */
    protected function getConfiguration($label, $placeholder, $options = []){
        return array_merge([
            'label'=>$label,
            'attr'=>[
                'placeholder'=>$placeholder
            ]
        ], $options);
    }
}