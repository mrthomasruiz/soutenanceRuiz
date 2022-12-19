<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\SousCategorie;
use App\Entity\SubCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SousCategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'label'=>'Ajouter une sous-catégorie',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez le titre de la sous-catégorie'
                ]
            ])
            ->add('categorie', EntityType::class, [
                'class'=>Categorie::class,
                'choice_label'=>'nom',
                'label'=>'Catégorie',
                'placeholder'=>'Selectionnez la catégorie',
                'required'=>false,

            ])
            ->add('Valider',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SousCategorie::class,
        ]);
    }
}
