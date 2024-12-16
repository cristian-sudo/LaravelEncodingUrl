<?php

namespace App\Interfaces;

interface UrlShortenerInterface
{
    public function encode(string $url): string;

    public function decode(string $shortUrl): string;
}
