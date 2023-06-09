<?php

namespace App\Form;

use App\Entity\Grave;
use App\Entity\Person;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewPersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'NAME',
            ])
            ->add('surname', TextType::class, [
                'label' => 'SURNAME',
            ])
            ->add('born', DateType::class, [
                'label' => 'BORN',
                'widget' => 'single_text',
            ])
            ->add('death', DateType::class, [
                'label' => 'DEATH',
                'widget' => 'single_text',
            ])
            ->add('grave', EntityType::class, [
                'label' => 'GRAVE',
                'class' => Grave::class,
                'choice_label' => 'id',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'SUBMIT'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
