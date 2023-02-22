<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Model\Aware;

/**
 * Interface MetaImageAwareInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Model\Aware
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface MetaImageAwareInterface
{
    /**
     * @return string|null
     */
    public function getMetaImage(): ?string;

    /**
     * @param string|null $metaImage
     */
    public function setMetaImage(?string $metaImage): void;
}
