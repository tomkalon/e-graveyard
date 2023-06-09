<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('modal_box')]
final class ModalBoxComponent
{
    public string $name; // modal-box data name
    public string $title; // modal box title
    public string $description; // modal box content
    public object $form;
    public object $grave; // entity
    public object $person; // entity
    public array $people; // array with person data
    public string $action;
    public string $target; // redirection address
    public string $submit; // submit button name
    public string $last_uri;
}
