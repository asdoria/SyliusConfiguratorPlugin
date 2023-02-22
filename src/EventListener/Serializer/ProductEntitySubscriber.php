<?php

namespace Asdoria\SyliusConfiguratorPlugin\EventListener\Serializer;

use App\Entity\Product\Product;
use Sylius\Component\Attribute\Model\Attribute;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductAttributeInterface;

/**
 * Class ProductEntitySubscriber
 * @package Asdoria\SyliusConfiguratorPlugin\EventListener\Serializer
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ProductEntitySubscriber extends AbstractEntitySubscriber
{
    protected static function getClassName(): string
    {
        return Product::class;
    }

    protected function getMethodNames(): array
    {
        return [];
    }
}
