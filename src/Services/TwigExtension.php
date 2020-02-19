<?php

namespace Frosh\WebP\Services;

use Twig\Extension\ExtensionInterface;
use Twig\TwigFilter;

class TwigExtension implements ExtensionInterface
{
    public function getTokenParsers()
    {
        return [];
    }

    public function getNodeVisitors()
    {
        return [];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('frosh_webp_url', [$this, 'generateWebPLink'])
        ];
    }

    public function getTests()
    {
        return [];
    }

    public function getFunctions()
    {
        return [];
    }

    public function getOperators()
    {
        return [];
    }

    public function generateWebPLink(string $url)
    {
        $path = pathinfo($url);

        if ($path['extension'] === 'svg') {
            return $url;
        }

        return $path['dirname'] . '/' . $path['filename'] . '.webp';
    }
}
