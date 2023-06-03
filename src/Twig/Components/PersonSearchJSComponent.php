<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('person_search_js')]
final class PersonSearchJSComponent
{
    public object $person;
    public array $people;
    public object $grave;
    public string $submit;
}
