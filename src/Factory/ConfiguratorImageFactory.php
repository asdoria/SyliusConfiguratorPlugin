<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Factory;


use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorImageInterface;
use Asdoria\SyliusConfiguratorPlugin\Factory\Model\ConfiguratorImageFactoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

/**
 * Class ConfiguratorImageFactory
 * @package Asdoria\SyliusConfiguratorPlugin\Factory
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorImageFactory implements ConfiguratorImageFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function createNew(): ConfiguratorImageInterface
    {
        /** @var ConfiguratorImageInterface $configuratorImage */
        $configuratorImage = $this->factory->createNew();

        return $configuratorImage;
    }

    /**
     * {@inheritdoc}
     */
    public function createForConfigurator(ConfiguratorInterface $configurator): ConfiguratorImageInterface
    {
        /** @var ConfiguratorImageInterface $configuratorImage */
        $configuratorImage = $this->createNew();
        $configuratorImage->setOwner($configurator);

        return $configuratorImage;
    }
}
