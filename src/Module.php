<?php

declare(strict_types=1);

namespace Laminas\Hydrator;

use Laminas\ModuleManager\ModuleManager;

class Module
{
    /**
     * Return default laminas-hydrator configuration for laminas-mvc applications.
     *
     * @return mixed[]
     */
    public function getConfig(): array
    {
        $provider = new ConfigProvider();

        return [
            'service_manager' => $provider->getDependencyConfig(),
        ];
    }

    /**
     * Register a specification for the HydratorManager with the ServiceListener.
     *
     * @deprecated ModuleManager support will be removed in version 5.0 of this component
     */
    public function init(ModuleManager $moduleManager): void
    {
        $event           = $moduleManager->getEvent();
        $container       = $event->getParam('ServiceManager');
        $serviceListener = $container->get('ServiceListener');

        $serviceListener->addServiceManager(
            'HydratorManager',
            'hydrators',
            HydratorProviderInterface::class,
            'getHydratorConfig'
        );
    }
}
