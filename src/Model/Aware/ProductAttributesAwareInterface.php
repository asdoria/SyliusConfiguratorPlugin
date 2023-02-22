<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Model\Aware;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Product\Model\ProductAttributeInterface;

/**
 * Interface ProductAttributesAwareInterface
 * @package Asdoria\SyliusProductAttributeConfiguratorPlugin\Model\Aware
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface ProductAttributesAwareInterface
{
    /**
     * @return Collection
     */
    public function getProductAttributes(): Collection;

    /**
     * @return bool
     */
    public function hasProductAttributes(): bool;

    /**
     * @param ProductAttributeInterface $attribute
     *
     * @return bool
     */
    public function hasProductAttribute(ProductAttributeInterface $attribute): bool;

    /**
     * @param ProductAttributeInterface $attribute
     */
    public function addProductAttribute(ProductAttributeInterface $attribute): void;

    /**
     * @param ProductAttributeInterface $attribute
     */
    public function removeProductAttribute(ProductAttributeInterface $attribute): void;

}
