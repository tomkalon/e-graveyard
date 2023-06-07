<?php

namespace App\Form;

use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchPersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('surname', TextType::class, [
                'required' => false
            ])
            ->add('born', DateType::class, [
                'required' => false,
                'widget' => 'choice',
                'placeholder' => [
                    'year' => 'Rok', 'month' => 'Miesiąc', 'day' => 'Dzień',
                ],
            ])
            ->add('death', DateType::class, [
                'required' => false,
                'widget' => 'choice',
                'placeholder' => [
                    'year' => 'Rok', 'month' => 'Miesiąc', 'day' => 'Dzień',
                ],
            ])
            ->add('search', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
