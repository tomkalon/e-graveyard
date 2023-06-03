<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('search_result')]
final class SearchResultComponent
{
    public array $search_result;
    public array $thead;
    public array $tbody;
    public string $page;
    public string $limit;
    public string $target;
    public string $type;
}
