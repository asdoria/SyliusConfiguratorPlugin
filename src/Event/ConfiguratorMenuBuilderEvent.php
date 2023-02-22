<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Event;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

class ConfiguratorMenuBuilderEvent extends MenuBuilderEvent
{
    /** @var ConfiguratorInterface */
    private ConfiguratorInterface $configurator;

    public function __construct(FactoryInterface $factory, ItemInterface $menu, ConfiguratorInterface $configurator)
    {
        parent::__construct($factory, $menu);

        $this->configurator = $configurator;
    }

    public function getConfigurator(): ConfiguratorInterface
    {
        return $this->configurator;
    }
}
