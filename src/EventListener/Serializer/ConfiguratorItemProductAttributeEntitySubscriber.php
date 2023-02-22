<?php

namespace Asdoria\SyliusConfiguratorPlugin\EventListener\Serializer;

use App\Entity\Product\Product;
use App\Entity\Product\ProductAttribute;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorItemProductAttribute;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorStep;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorStepInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Attribute\Model\Attribute;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Model\ProductAttributeInterface;

/**
 * Class ConfiguratorStepEntitySubscriber
 * @package Asdoria\SyliusConfiguratorPlugin\EventListener\Serializer
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorItemProductAttributeEntitySubscriber extends AbstractEntitySubscriber
{
    protected static function getClassName(): string
    {
        return ConfiguratorItemProductAttribute::class;
    }

    protected function getMethodNames(): array
    {
        return [
            'getProductAttribute'  => ['AddToCartItem'],
            'getDiscr'  => ['AddToCartItem'],
        ];
    }

    /**
     * @param ConfiguratorItemProductAttribute $item
     *
     * @return ProductAttributeInterface|null
     */
    public function getProductAttribute(ConfiguratorItemProductAttribute $item): ?ProductAttributeInterface
    {
        return $item->getProductAttribute();
    }

    /**
     * @param ConfiguratorItemProductAttribute $item
     *
     * @return string
     */
    public function getDiscr(ConfiguratorItemProductAttribute $item): string
    {
        return 'product_attribute';
    }


}
