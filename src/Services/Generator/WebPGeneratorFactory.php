<?php

namespace Frosh\WebP\Services\Generator;

class WebPGeneratorFactory
{
    /**
     * @param WebPGeneratorInterface[] $generators
     */
    public static function factory(iterable $generators): WebPGeneratorInterface
    {
        foreach ($generators as $generator) {
            if ($generator->isCompatible()) {
                return $generator;
            }
        }

        throw new \RuntimeException('Cannot find any compatible webp generator on this system');
    }
}
