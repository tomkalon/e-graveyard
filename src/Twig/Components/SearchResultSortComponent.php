<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('search_result_sort')]
final class SearchResultSortComponent
{
    public object $form;
}
