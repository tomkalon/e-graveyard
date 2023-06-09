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
                'label' => 'NAME',
                'required' => true,
            ])
            ->add('surname', TextType::class, [
                'label' => 'SURNAME',
                'required' => false
            ])
            ->add('born', DateType::class, [
                'label' => 'BORN',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('death', DateType::class, [
                'label' => 'DEATH',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('search', SubmitType::class, [
                'label' => 'SEARCH'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
