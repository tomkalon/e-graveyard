<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('person_search')]
final class PersonSearchComponent
{
    public object $form;
}
