<?php

namespace Frosh\WebP\Services\Generator;

class Php implements WebPGeneratorInterface
{
    public function generate($thumbnail, int $quality): string
    {
        ob_start();
        imagewebp($thumbnail, null, $quality);
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    public function isCompatible(): bool
    {
        return function_exists('imagewebp');
    }
}
