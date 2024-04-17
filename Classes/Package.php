<?php
namespace Carbon\Eel;

use Neos\Flow\Cache\CacheManager;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Monitor\FileMonitor;
use Neos\Flow\Package\Package as BasePackage;

class Package extends BasePackage
{
    /**
     * @param Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(Bootstrap $bootstrap)
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();

        $flushTailwindCache = function () use ($bootstrap) {
            $cacheManager = $bootstrap->getEarlyInstance(CacheManager::class);
            $cacheManager->getCache('Carbon_Eel_Tailwind')->flush();
        };

        $dispatcher->connect(FileMonitor::class, 'filesHaveChanged', function ($fileMonitorIdentifier, array $changedFiles) use ($flushTailwindCache) {
            if ($fileMonitorIdentifier == 'Flow_ConfigurationFiles') {
                $flushTailwindCache();
            }
        });
    }
}
