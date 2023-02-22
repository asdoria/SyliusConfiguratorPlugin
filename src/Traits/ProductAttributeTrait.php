<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits;


use Sylius\Component\Product\Model\ProductAttributeInterface;

/**
 * Trait ProductAttributeTrait
 * @package Asdoria\SyliusFacetFilterPlugin\Traits
 */
trait ProductAttributeTrait
{
    /**
     * @var ProductAttributeInterface|null
     */
    protected $productAttribute;

    /**
     * @return ProductAttributeInterface|null
     */
    public function getProductAttribute(): ?ProductAttributeInterface
    {
        return $this->productAttribute;
    }

    /**
     * @param ProductAttributeInterface|null $productAttribute
     */
    public function setProductAttribute(?ProductAttributeInterface $productAttribute): void
    {
        $this->productAttribute = $productAttribute;
    }
}
