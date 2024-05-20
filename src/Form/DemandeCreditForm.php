<?php

// src/Form/CustomFormType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class DemandeCreditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montant', NumberType::class, [
                'label' => 'Montant du credit',
            ])

            ->add('raison', TextType::class, [
                'label' => 'Raison',
            ])
            
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer',
            ]);
    }
}
