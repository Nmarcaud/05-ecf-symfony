<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AddProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pictureUrl')

            ->add('firstname', TextType::class, ['label' => 'Prénom'])
            ->add('lastname')

            ->add('status')
            ->add('jobTitle')

            ->add('email')
            ->add('password')

            ->add('phone')
            
            ->add('adresse')
            ->add('zipCode')
            ->add('city')
            
            ->add('apsideBirthday')
            
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter le Profil'
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
