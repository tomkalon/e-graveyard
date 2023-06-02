<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('modal_box')]
final class ModalBoxComponent
{
    public string $name;
    public string $title;
    public string $description;
    public object $form;
    public object $grave;
    public object $person;
    public array $people;
    public object $component;
    public string $action;
}
