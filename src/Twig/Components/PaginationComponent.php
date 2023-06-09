<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('pagination')]
final class PaginationComponent
{
    public string $search_result_length;
    public string $limit;
    public string $page;
}
