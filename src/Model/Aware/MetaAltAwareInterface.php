<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Model\Aware;

/**
 * Interface MetaAltAwareInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Model\Aware
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface MetaAltAwareInterface
{
    /**
     * @return string|null
     */
    public function getMetaAlt(): ?string;

    /**
     * @param string|null $metaAlt
     */
    public function setMetaAlt(?string $metaAlt): void;
}
