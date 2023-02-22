<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Model\Aware;

/**
 * Interface MetaCanonicalAwareInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Model\Aware
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface MetaCanonicalAwareInterface
{
    /**
     * @return string|null
     */
    public function getMetaCanonical(): ?string;

    /**
     * @param string|null $metaCanonical
     */
    public function setMetaCanonical(?string $metaCanonical): void;
}
