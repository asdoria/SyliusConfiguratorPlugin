<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Model\Aware;

use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemInterface;

/**
 * Class ConfiguratorItemAwareInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Model\Aware
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface ConfiguratorItemAwareInterface
{
    /**
     * @return ConfiguratorItemInterface|null
     */
    public function getConfiguratorItem(): ?ConfiguratorItemInterface;

    /**
     * @param ConfiguratorItemInterface|null $configuratorItem
     *
     * @return void
     */
    public function setConfiguratorItem(?ConfiguratorItemInterface $configuratorItem): void;
}
