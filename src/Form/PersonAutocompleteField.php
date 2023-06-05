<?php

namespace App\Form;

use App\Entity\Person;
use App\Repository\PersonRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class PersonAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Person::class,
            'placeholder' => 'Choose a Person',
            //'choice_label' => 'name',

            'query_builder' => function(PersonRepository $personRepository) {
                return $personRepository->createQueryBuilder('person');
            },
            //'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
