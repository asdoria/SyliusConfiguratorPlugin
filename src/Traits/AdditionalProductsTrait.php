<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Product\Model\ProductInterface;


/**
 * Trait AdditionalProductsTrait
 * @package App\Traits
 */
trait AdditionalProductsTrait
{
    /**
     * @var Collection
     */
    protected Collection $additionalProducts;


    /**
     * AdditionalProductsTrait constructor.
     */
    public function initializeAdditionalProductsCollection()
    {
        $this->additionalProducts = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getAdditionalProducts(): Collection
    {
        return $this->additionalProducts;
    }

    /**
     * @return bool
     */
    public function hasAdditionalProducts(): bool
    {
        return !$this->additionalProducts->isEmpty();
    }

    /**
     * @param AdditionalProductInterface $additionalProduct
     *
     * @return bool
     */
    public function hasAdditionalProduct(ProductInterface $additionalProduct): bool
    {
        return $this->additionalProducts->contains($additionalProduct);
    }

    /**
     * @param AdditionalProductInterface $additionalProduct
     */
    public function addAdditionalProduct(ProductInterface $additionalProduct): void
    {
        if (!$this->hasAdditionalProduct($additionalProduct)) {
            $this->additionalProducts->add($additionalProduct);
        }
    }

    /**
     * @param AdditionalProductInterface $additionalProduct
     */
    public function removeAdditionalProduct(ProductInterface $additionalProduct): void
    {
        if ($this->hasAdditionalProduct($additionalProduct)) {
            $this->additionalProducts->removeElement($additionalProduct);
        }
    }
}
