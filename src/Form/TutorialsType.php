<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Tutorials;

use App\Repository\CategoriesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;


class TutorialsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('title',null,[
                'empty_data' => '',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un titre',
                    ]),
                ]
            ])
            ->add('description',null,[
                'empty_data' => '',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une description',
                    ]),
                ]
            ])
            ->add('imageFile',FileType::class,[
                'required' => false
            ])
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function (CategoriesRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->andWhere('c.status != :status')
                        ->setParameter('status', 'off');
                },

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tutorials::class,
        ]);
    }
}
