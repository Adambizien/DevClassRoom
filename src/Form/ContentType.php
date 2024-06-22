<?php

namespace App\Form;

use App\Entity\Chapter;
use App\Entity\Content;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


class ContentType extends AbstractType
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
            ->add('text')
            ->add('code')
            ->add('video', null, [
                'constraints' => [
                    new Callback(function ($value, ExecutionContextInterface $context) {
                        if (null === $value || '' === $value) {
                            return;
                        }

                        $pattern = '/^(https?\:\/\/)?(www\.youtube\.com|youtu\.be)\/.+$/';

                        if (!preg_match($pattern, $value)) {
                            $context->buildViolation('Le lien "{{ value }}" n\'est pas un lien YouTube valide.')
                                ->setParameter('{{ value }}', $value)
                                ->addViolation();
                        }
                    }),
                ],
            ])
            ->add('imageFile',FileType::class,[
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Content::class,
        ]);
    }
}
