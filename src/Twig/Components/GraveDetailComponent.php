<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('grave_detail')]
final class GraveDetailComponent
{
    public object $grave;
    public object $person;
    public array $people;
    public object $form_add_person;
    public object $form_remove_person;
}
