<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Model\Aware;

/**
 * Interface MetaRobotsAwareInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Model\Aware
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface MetaRobotsAwareInterface
{
    /**
     * @return string|null
     */
    public function getMetaRobots(): ?string;

    /**
     * @param string|null $metaRobots
     */
    public function setMetaRobots(?string $metaRobots): void;
}
