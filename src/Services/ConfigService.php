<?php

namespace Frosh\WebP\Services;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\HttpFoundation\RequestStack;

class ConfigService
{
    /**
     * @var SystemConfigService
     */
    private $configService;

    public function __construct(SystemConfigService $configService)
    {
        $this->configService = $configService;
    }

    public function isPluginActive(?string $salesChannelId): bool
    {
        $ret = $this->configService->get('FroshPlatformWebP.config.enable', $salesChannelId);

        if ($ret === null) {
            $ret = false;
        }

        return $ret;
    }
}
