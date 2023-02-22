<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Model\Aware;

use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;

/**
 * Interface ConfiguratorAwareInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Model\Aware
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface ConfiguratorAwareInterface
{
    public function getConfigurator(): ?ConfiguratorInterface;

    public function setConfigurator(?ConfiguratorInterface $configurator): void;
}
