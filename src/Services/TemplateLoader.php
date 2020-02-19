<?php

namespace Frosh\WebP\Services;

use Shopware\Core\PlatformRequest;
use Symfony\Bundle\TwigBundle\Loader\NativeFilesystemLoader;
use Symfony\Component\HttpFoundation\RequestStack;

class TemplateLoader extends NativeFilesystemLoader
{
    /**
     * @var ConfigService
     */
    private $configService;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(
        NativeFilesystemLoader $filesystemLoader,
        ConfigService $configService,
        RequestStack $requestStack
    ) {
        $this->configService = $configService;
        $this->requestStack = $requestStack;
        parent::__construct([], null);

        foreach ($filesystemLoader->getNamespaces() as $namespace) {
            if (
                strpos($namespace, 'FroshPlatformWebP') !== false &&
                !$configService->isPluginActive($this->tryToGetSalesChannelContext())
            ) {
                continue;
            }

            $this->setPaths($filesystemLoader->getPaths($namespace), $namespace);
        }
    }

    public function addPath($path, $namespace = self::MAIN_NAMESPACE)
    {
        if (
            strpos($path, 'FroshPlatformWebP') !== false &&
            !$this->configService->isPluginActive($this->tryToGetSalesChannelContext())
        ) {
            return;
        }

        parent::addPath($path, $namespace);
    }

    private function tryToGetSalesChannelContext(): ?string
    {
        if ($this->requestStack->getMasterRequest() === null) {
            return null;
        }

        if (!$this->requestStack->getMasterRequest()->attributes->has(PlatformRequest::ATTRIBUTE_SALES_CHANNEL_ID)) {
            return null;
        }

        return $this->requestStack->getMasterRequest()->attributes->get(PlatformRequest::ATTRIBUTE_SALES_CHANNEL_ID);
    }
}
