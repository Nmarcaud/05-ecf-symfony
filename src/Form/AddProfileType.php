<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Status;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AddProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pictureUrl')

            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => true
                ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'required' => true
                ])

            ->add('status', EntityType::class, [
                'class' => Status::class,
                'label' => 'Status'
                ])
            ->add('jobTitle', TextType::class, ['label' => 'Intitulé du poste'])
            ->add('disponibility', ChoiceType::class, [
                'label' => 'Disponibilité',
                'choices' => [
                    'Disponible' => true,
                    'Non Disponible' => false
                ]
            ])

            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true
                ])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe'])

            ->add('phone', TextType::class, ['label' => 'Téléphone'])
            
            ->add('adresse', TextType::class, ['label' => 'Adresse'])
            ->add('zipCode', TextType::class, ['label' => 'Code Postal'])
            ->add('city', TextType::class, ['label' => 'Ville'])
            
            ->add('apsideBirthday', DateType::class, [
                'label' => 'Date d\'entrée chez Apside'
                ])
            
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter le profil'
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
