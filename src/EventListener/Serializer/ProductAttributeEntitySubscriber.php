<?php

namespace Asdoria\SyliusConfiguratorPlugin\EventListener\Serializer;

use App\Entity\Product\ProductAttribute;
use Sylius\Component\Attribute\Model\Attribute;
use Sylius\Component\Product\Model\ProductAttributeInterface;

/**
 * Class ProductAttributeEntitySubscriber
 * @package Asdoria\SyliusConfiguratorPlugin\EventListener\Serializer
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ProductAttributeEntitySubscriber extends AbstractEntitySubscriber
{
    protected static function getClassName(): string
    {
        return ProductAttribute::class;
    }

    protected function getMethodNames(): array
    {
        return [
            'getName'  => ['AddToCartItem'],
            'getCode'  => ['AddToCartItem'],
            'getType'  => ['AddToCartItem'],
            'getConfiguration'  => ['AddToCartItem'],
        ];
    }

    /**
     * @param ProductAttributeInterface $attribute
     *
     * @return string|null
     */
    public function getName(ProductAttributeInterface $attribute): ?string
    {
        return $attribute->getName();
    }

    /**
     * @param ProductAttributeInterface $attribute
     *
     * @return string|null
     */
    public function getCode(ProductAttributeInterface $attribute): ?string
    {
        return $attribute->getCode();
    }

    /**
     * @param ProductAttributeInterface $attribute
     *
     * @return string|null
     */
    public function getType(ProductAttributeInterface $attribute): ?string
    {
        return $attribute->getType();
    }

    /**
     * @param ProductAttributeInterface $attribute
     *
     * @return array
     */
    public function getConfiguration(ProductAttributeInterface $attribute): array
    {
        return $attribute->getConfiguration();
    }

}
