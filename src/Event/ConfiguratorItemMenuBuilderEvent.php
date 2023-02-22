<?php

declare(strict_types=1);


namespace Asdoria\SyliusConfiguratorPlugin\Event;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

class ConfiguratorItemMenuBuilderEvent extends MenuBuilderEvent
{
    /** @var ConfiguratorItemInterface */
    private ConfiguratorItemInterface $configurator;

    public function __construct(FactoryInterface $factory, ItemInterface $menu, ConfiguratorItemInterface $configurator)
    {
        parent::__construct($factory, $menu);

        $this->configurator = $configurator;
    }

    public function getConfiguratorItem(): ConfiguratorItemInterface
    {
        return $this->configurator;
    }
}
