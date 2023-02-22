<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Model\Aware;

use Sylius\Component\Product\Model\ProductAttributeInterface;

/**
 * Class ProductAttributeAwareInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Model\Aware
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface ProductAttributeAwareInterface
{
    /**
     * @return ProductAttributeInterface|null
     */
    public function getProductAttribute(): ?ProductAttributeInterface;

    /**
     * @param ProductAttributeInterface|null $productAttribute
     */
    public function setProductAttribute(?ProductAttributeInterface $productAttribute): void;
}
