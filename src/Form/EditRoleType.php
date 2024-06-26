<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EditRoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('role', ChoiceType::class, [
            'choices' => [
                'Admin' => 'ROLE_ADMIN',
                'Utilisateur' => 'ROLE_USER',
                'Attente de Validation' => 'ROLE_AWAITING_VALIDATION',
                'Formateur' => 'ROLE_TEACHER',
            ],
            'expanded' => false,
            'multiple' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
