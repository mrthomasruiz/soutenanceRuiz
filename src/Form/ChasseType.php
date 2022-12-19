<?php

namespace App\Form;

use App\Entity\Chasse;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ChasseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label'=>'Quoi ?',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez le type de produit recherché'
                ]
            ])

            ->add('photo', FileType::class,[
                'label'=>'Photo',
                'required'=>false,
                'constraints'=>[
                    new File([
                        'mimeTypes'=>[
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                            'image/gif',
                            'image/jfif',
                            'image/webp',
                            'image/avif'
                        ],
                        'mimeTypesMessage'=>'Format non géré'
                    ])
                ]
            ])


            ->add('criteres', TextareaType::class,[
                'label'=>'Comment ?',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez les caractéristiques du produit recherché'
                ]
            ])


            ->add('budget', NumberType::class,[
                'label'=>'Combien ?',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez votre budget maximum'
                ]
            ])

            ->add('deadline', DateType::class,[
                'label'=>'Quand ?',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>''
                ]
            ])


            ->add('email', EmailType::class,[
                'label'=>'Qui ?',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez votre Email'
                ]
            ])

            ->add('Soumettre', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chasse::class,
        ]);
    }
}
