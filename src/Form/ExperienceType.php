<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\Experience;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, ['label' => 'Description du poste'])
            ->add('startDate', DateType::class, ['label' => 'Date de Début'])
            ->add('endDate', DateType::class, ['label' => 'Date de Fin'])
            ->add('title', TextType::class, ['label' => 'Titre du poste occupé'])
            ->add('Entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'label' => 'Compétence'
                ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Experience::class,
        ]);
    }
}
