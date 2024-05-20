<?php

// src/Form/CustomFormType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ReclamationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre du reclamation',
            ])
            ->add('description', TextType::class, [
                'label' => 'Description du reclamation',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer',
            ]);
    }
}
