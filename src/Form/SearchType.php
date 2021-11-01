<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', TextType::class, [
                'label' => 'Recheche',
                'attr' => [
                    'placeholder' => 'Rechercher par Nom...',
                    'class' => 'form-control me-2 py-3',
                    'type' => 'search'
                    ]
            ]);
    }  
}
