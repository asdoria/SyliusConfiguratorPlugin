<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Model\Aware;

use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorStepInterface;

/**
 * Interface ConfiguratorStepAwareInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Model\Aware
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface ConfiguratorStepAwareInterface
{
    public function getConfiguratorStep(): ?ConfiguratorStepInterface;

    public function setConfiguratorStep(?ConfiguratorStepInterface $configurator): void;
}
