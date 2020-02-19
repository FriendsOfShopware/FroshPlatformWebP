<?php

namespace Frosh\WebP\Services\Generator;

interface WebPGeneratorInterface
{
    public function generate($thumbnail, int $quality): string;

    public function isCompatible(): bool;
}
