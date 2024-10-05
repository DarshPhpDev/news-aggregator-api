<?php

namespace App\Services\Contracts;

interface ArticleFetcherInterface
{
    /* Common method that will be implemented on each fetcher */
    public function fetch(): array;
}