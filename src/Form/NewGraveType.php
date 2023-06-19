<?php

namespace App\Form;

use App\Entity\Grave;
use App\Entity\Graveyard;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewGraveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sector', NumberType::class, [
                'label' => 'SECTOR',
                'required' => true
            ])
            ->add('row', NumberType::class, [
                'label' => 'ROW',
                'required' => false
            ])
            ->add('number', NumberType::class, [
                'label' => 'NUMBER',
                'required' => true
            ])
            ->add('positionX', TextType::class, [
                'label' => 'POSITION_X',
                'required' => true
            ])
            ->add('positionY', TextType::class, [
                'label' => 'POSITION_Y',
                'required' => true
            ])
            ->add('graveyard', EntityType::class, [
                'label' => 'GRAVEYARD',
                'required' => true,
                'class' => Graveyard::class,
                'choice_label' => 'name',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'SUBMIT'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Grave::class,
        ]);
    }
}
