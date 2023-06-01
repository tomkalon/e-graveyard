<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('grave_detail')]
final class GraveDetailComponent
{
    public object $person;
    public object $grave;
}
