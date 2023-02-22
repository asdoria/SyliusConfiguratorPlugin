<?php

declare(strict_types=1);


namespace Asdoria\SyliusConfiguratorPlugin\Factory;

use Asdoria\SyliusConfiguratorPlugin\Factory\Model\ConfiguratorItemFactoryInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemInterface;

/**
 * Class ConfiguratorItemFactory
 * @package Asdoria\SyliusConfiguratorPlugin\Factory
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorItemFactory implements ConfiguratorItemFactoryInterface
{
    /** @var string */
    private $className;

    /**
     * MatrixConfiguratorItemGroupFactory constructor.
     *
     * @param string $className
     */
    public function __construct(string $className)
    {
        $this->className = $className;
    }

    /**
     * {@inheritdoc}
     */
    public function createNew(): ConfiguratorItemInterface
    {
        return new $this->className();
    }

    /**
     * @param ConfiguratorInterface $configurator
     *
     * @return ConfiguratorItemInterface
     */
    public function createForConfigurator(ConfiguratorInterface $configurator): ConfiguratorItemInterface
    {
        $configuratorItem = $this->createNew();
        $configuratorItem->setConfigurator($configurator);

        return $configuratorItem;
    }
}
