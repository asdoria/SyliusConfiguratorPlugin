<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Factory;


use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorStepImageInterface;
use Asdoria\SyliusConfiguratorPlugin\Factory\Model\ConfiguratorStepImageFactoryInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

/**
 * Class ConfiguratorItemImageFactory
 * @package Asdoria\SyliusConfiguratorPlugin\Factory
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorStepImageFactory implements ConfiguratorStepImageFactoryInterface
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
    public function createNew(): ConfiguratorStepImageInterface
    {
        /** @var ConfiguratorStepImageInterface $configuratorImage */
        $configuratorImage = $this->factory->createNew();

        return $configuratorImage;
    }

    /**
     * {@inheritdoc}
     */
    public function createForStep(ConfiguratorStepInterface $step): ConfiguratorStepImageInterface
    {
        /** @var ConfiguratorStepImageInterface $configuratorImage */
        $configuratorImage = $this->createNew();
        $configuratorImage->setOwner($step);

        return $configuratorImage;
    }
}
