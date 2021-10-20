<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Skill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CandidatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, ['label' => 'Prénom'])
            ->add('lastname')
            ->add('email')
            ->add('adresse')
            ->add('zip_code')
            ->add('city')
            ->add('phone')
            // ->add('pictureUrl')
            // ->add('userSkills')
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter la compétence'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
