<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Status;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, ['label' => 'Prénom'])
            ->add('lastname', TextType::class, ['label' => 'Nom'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('adresse', TextType::class, ['label' => 'N° et Rue'])
            ->add('zip_code', TextType::class, ['label' => 'Code Postal'])
            ->add('city', TextType::class, ['label' => 'Ville'])
            ->add('phone', TextType::class, ['label' => 'Téléphone'])
            ->add('jobTitle', TextType::class, ['label' => 'Intitulé du poste'])
            ->add('disponibility', ChoiceType::class, [
                'label' => 'Disponibilité',
                'choices' => [
                    'Disponible' => true,
                    'Non Disponible' => false
                ]
            ])
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'label' => 'Téléphone'
                ])
            // ->add('pictureUrl')
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier'
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
