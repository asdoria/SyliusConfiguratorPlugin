<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Model\Aware;

/**
 * Interface MetaTitleAwareInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Model\Aware
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface MetaTitleAwareInterface
{
    /**
     * @return string|null
     */
    public function getMetaTitle(): ?string;

    /**
     * @param string|null $metaTitle
     */
    public function setMetaTitle(?string $metaTitle): void;
}
