<?php

namespace App\Form;

use App\Entity\Grave;
use App\Entity\Graveyard;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewGraveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sector', TextType::class, [
                'required' => true
            ])
            ->add('row', TextType::class, [
                'required' => false
            ])
            ->add('number', TextType::class, [
                'required' => true
            ])
            ->add('positionX', TextType::class, [
                'required' => false
            ])
            ->add('positionY', TextType::class, [
                'required' => false
            ])
            ->add('graveyard', EntityType::class, [
                'required' => true,
                'class' => Graveyard::class,
                'choice_label' => 'name',
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Grave::class,
        ]);
    }
}
