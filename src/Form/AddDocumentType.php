<?php

namespace App\Form;

use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class AddDocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
            'label' => 'Document (PDF de préférence)',
            ])
            ->add('name', TextType::class, [
                'label' => 'Renomer le document',
                'help' => 'Vous pouvez renommer le document ici',
                'required' => false
                ])
            // ->add('url', UrlType::class, ['label' => 'Url'])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter le document'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
