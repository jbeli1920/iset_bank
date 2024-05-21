<?php

// src/Form/CustomFormType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class CreditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nbr_mois', NumberType::class, [
                'label' => 'Mois',
            ])

            ->add('montant_mois', NumberType::class, [
                'label' => 'Montant',
            ])
            
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer',
            ]);
    }
}
