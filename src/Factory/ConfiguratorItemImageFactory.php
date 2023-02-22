<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Factory;


use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemImageInterface;
use Asdoria\SyliusConfiguratorPlugin\Factory\Model\ConfiguratorItemImageFactoryInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

/**
 * Class ConfiguratorItemImageFactory
 * @package Asdoria\SyliusConfiguratorPlugin\Factory
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorItemImageFactory implements ConfiguratorItemImageFactoryInterface
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
    public function createNew(): ConfiguratorItemImageInterface
    {
        /** @var ConfiguratorItemImageInterface $configuratorImage */
        $configuratorImage = $this->factory->createNew();

        return $configuratorImage;
    }

    /**
     * {@inheritdoc}
     */
    public function createForItem(ConfiguratorItemInterface $item): ConfiguratorItemImageInterface
    {
        /** @var ConfiguratorItemImageInterface $configuratorImage */
        $configuratorImage = $this->createNew();
        $configuratorImage->setOwner($item);

        return $configuratorImage;
    }
}
