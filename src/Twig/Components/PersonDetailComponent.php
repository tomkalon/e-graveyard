<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('person_detail')]
final class PersonDetailComponent
{
    public object $person;
    public array $people;
    public string $last_uri;
}
