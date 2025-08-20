<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Wish;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du souhait',
                'attr' => ['placeholder' => 'Entrez le titre du souhait']
            ])
            ->add('description', TextType::class, [
                'label' => 'Description du souhait',
                'attr' => ['placeholder' => 'Entrez une description détaillée du souhait']
            ])
            ->add('author',
                TextType::class,
                [
                    'label' => 'Auteur du souhait',
                    'attr' => ['placeholder' => 'Entrez le nom de l\'auteur']
                ])
            ->add('category', EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => 'name',
                    'query_builder' => function (CategoryRepository $repo) {
                        return $repo->createQueryBuilder('s' )
                            ->orderBy('s.name', 'ASC');
                    },
                    'placeholder' => 'Sélectionnez une catégorie',
                    'required' => true,
                    'label' => 'Catégorie du souhait',
                ])
            ->add('submit', SubmitType::class,
                [
                'label' => 'Enregistrer le souhait',
                'attr' => ['class' => 'btn btn-primary']
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
