<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'USERNAME'
            ])
            ->add('email', EmailType::class, [
                'label' => 'EMAIL'
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'invalid_message' => 'INCOMPATIBLE_PASSWORD',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'first_options' => [
                    'label' => 'PASSWORD',
                    'mapped' => false
                ],
                'second_options' => [
                  'label' => 'REPEAT_PASSWORD',
                  'mapped' => false
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'ENTER_PASSWORD',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'ADD',
                'attr' => ['class' => 'btn btn-success float-end']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
