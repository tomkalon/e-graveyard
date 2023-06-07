<?php

namespace App\Form;

use App\Entity\Grave;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchGraveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('graveyard', EntityType::class, [
                'class' => Grave::class,
                'choice_label' => 'name',
                'multiple' => false,
                'require' => true
            ])
            ->add('sector',TextType::class, [
                'require' => false,
            ])
            ->add('row',TextType::class, [
                'require' => false,
            ])
            ->add('number',TextType::class, [
                'require' => false,
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
