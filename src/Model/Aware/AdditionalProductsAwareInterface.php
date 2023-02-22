<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Model\Aware;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ProductInterface;

/**
 * Interface AdditionalProductsAwareInterface
 * @package Asdoria\SyliusProductPlugin\Model\Aware
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface AdditionalProductsAwareInterface
{
    /**
     * @return Collection
     */
    public function getAdditionalProducts(): Collection;

    /**
     * @return bool
     */
    public function hasAdditionalProducts(): bool;

    /**
     * @param ProductInterface $product
     *
     * @return bool
     */
    public function hasAdditionalProduct(ProductInterface $product): bool;

    /**
     * @param ProductInterface $product
     */
    public function addAdditionalProduct(ProductInterface $product): void;

    /**
     * @param ProductInterface $product
     */
    public function removeAdditionalProduct(ProductInterface $product): void;

}
