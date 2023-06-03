<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('person_add')]
final class PersonAddComponent
{
    public object $form;
    public string $id;
    public string $last_uri;
    public string $close;
    public string $submit;
}
