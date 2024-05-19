<?php

// src/Form/CustomFormType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class TransactionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('destinataire', TextType::class, [
                'label' => 'Destinataire',
            ])
            ->add('montant', NumberType::class, [
                'label' => 'Montant',
            ])
            ->add('mot_de_passe', TextType::class, [
                'label' => 'Mot de passe',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer',
            ]);
    }
}
