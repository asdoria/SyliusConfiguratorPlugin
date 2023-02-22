<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits;

use Sylius\Component\Product\Model\ProductInterface;

/**
 * Trait ProductAwareTrait
 * @package Asdoria\SyliusConfiguratorPlugin\Traits
 *
 * @author  Hugo Duval <hugo.duval@asdoria.com>
 */
trait ProductTrait
{
    /**
     * @var ProductInterface|null
     */
    protected $product;

    /**
     * @return ProductInterface|null
     */
    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    /**
     * @param ProductInterface|null $product
     */
    public function setProduct(?ProductInterface $product): void
    {
        $this->product = $product;
    }
}
