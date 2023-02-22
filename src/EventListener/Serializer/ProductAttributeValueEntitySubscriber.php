<?php

namespace Asdoria\SyliusConfiguratorPlugin\EventListener\Serializer;

use Asdoria\SyliusConfiguratorPlugin\Context\Model\ConfiguratorContextInterface;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorItemProductAttribute;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Attribute\Model\Attribute;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Model\ProductAttributeInterface;
use App\Entity\Product\ProductAttributeValue;
use Sylius\Component\Product\Model\ProductAttributeValueInterface;

/**
 * Class ProductVariantEntitySubscriber
 * @package Asdoria\SyliusConfiguratorPlugin\EventListener\Serializer
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ProductAttributeValueEntitySubscriber extends AbstractEntitySubscriber
{

    protected static function getClassName(): string
    {
        return ProductAttributeValue::class;
    }

    protected function getMethodNames(): array
    {
        return [
            'getAttributeCode' => ['AddToCartItem'],
            'getValue'         => ['AddToCartItem'],
        ];
    }

    /**
     * @param ProductAttributeValue $value
     *
     * @return string|null
     */
    public function getAttributeCode(ProductAttributeValue $value): ?string
    {
        return $value->getAttribute()->getCode();
    }

    /**
     * @param ProductVariantInterface $variant
     *
     * @return string|null
     */
    public function getValue(ProductAttributeValue $value)
    {
        return $value->getValue();
    }

}
