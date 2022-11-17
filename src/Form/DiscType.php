<?php

namespace App\Form;

use App\Entity\Disc;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class DiscType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('picture', FileType::class,[
                'label' => 'Image',
                'mapped' => false,
                'required' => false, 
                'constraints' => [
                    new Assert\Image([
                        'mimeTypesMessage' => 'Inserez une photo format jpg, jpeg ou png',
                    ])
                ]
            ])
            ->add('label')
            ->add('price')
            ->add('Artist')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Disc::class,
        ]);
    }
}
