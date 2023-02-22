<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits;

use Sylius\Component\Core\Model\ProductInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Trait ProductsTrait
 * @package App\Traits
 */
trait ProductsTrait
{
    /**
     * @var Collection|null
     */
    protected Collection $products;

    /**
     * ProductProducts constructor
     */
    public function initializeProductsCollection(): void
    {
        $this->products = new ArrayCollection();
    }

    /**
     * @return Collection|null
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @return bool
     */
    public function hasProducts(): bool
    {
        return !$this->products->isEmpty();
    }

    /**
     * @param ProductInterface $product
     *
     * @return bool
     */
    public function hasProduct(ProductInterface $product): bool
    {
        return $this->products->contains($product);
    }

    /**
     * @param ProductInterface $product
     */
    public function addProduct(ProductInterface $product): void
    {
        $this->products->add($product);
    }

    /**
     * @param ProductInterface $product
     */
    public function removeProduct(ProductInterface $product): void
    {
        if ($this->hasProduct($product)) {
            $this->products->removeElement($product);
        }
    }
}
