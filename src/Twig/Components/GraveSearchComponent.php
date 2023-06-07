<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('grave_search')]
final class GraveSearchComponent
{
    public object $form;
    public string $last_uri;
}
