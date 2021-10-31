<?php

namespace App\Form;

use App\Entity\Skill;
use App\Entity\UserSkill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserSkillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('skill', EntityType::class, [
                'class' => Skill::class,
                'label' => 'Compétence'
                ])
            ->add('apprecied', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'label' => 'Appréciée'
            ])
            ->add('level', RangeType::class, [
                'label' => 'Niveau',
                'attr' => [
                    'min' => 1,
                    'max' => 5
                ],
                'help' => 'Entre 1 et 5',
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserSkill::class,
        ]);
    }
}
