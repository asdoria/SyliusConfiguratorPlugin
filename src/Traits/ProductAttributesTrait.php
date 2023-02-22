<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Product\Model\ProductAttributeInterface;

/**
 * Trait ProductAttributesTrait
 * @package App\Traits
 */
trait ProductAttributesTrait
{
    /**
     * @var Collection
     */
    protected Collection $productAttributes;

    /**
     * ProductProductAttributes constructor
     */
    public function initializeProductAttributesCollection(): void
    {
        $this->productAttributes = new ArrayCollection();
    }

    /***
     * @return Collection
     */
    public function getProductAttributes(): Collection
    {
        return $this->productAttributes;
    }

    /**
     * @return bool
     */
    public function hasProductAttributes(): bool
    {
        return !$this->productAttributes->isEmpty();
    }

    /**
     * @param ProductAttributeInterface $productAttribute
     *
     * @return bool
     */
    public function hasProductAttribute(ProductAttributeInterface $productAttribute): bool
    {
        return $this->productAttributes->contains($productAttribute);
    }

    /**
     * @param ProductAttributeInterface $productAttribute
     */
    public function addProductAttribute(ProductAttributeInterface $productAttribute): void
    {
        $this->productAttributes->add($productAttribute);
    }

    /**
     * @param ProductAttributeInterface $productAttribute
     */
    public function removeProductAttribute(ProductAttributeInterface $productAttribute): void
    {
        if ($this->hasProductAttribute($productAttribute)) {
            $this->productAttributes->removeElement($productAttribute);
        }
    }
}
