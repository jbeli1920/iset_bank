<?php

// src/Form/CustomFormType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ReponseReclamation extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reponse', TextType::class, [
                'label' => 'Reponse',
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer',
            ]);
    }
}
