<?php

namespace Asdoria\SyliusConfiguratorPlugin\EventListener\Serializer;

use App\Entity\Product\Product;
use App\Entity\Product\ProductAttribute;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorItemAdditionalProduct;
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
class ConfiguratorItemAdditionalProductEntitySubscriber extends AbstractEntitySubscriber
{
    protected static function getClassName(): string
    {
        return ConfiguratorItemAdditionalProduct::class;
    }

    protected function getMethodNames(): array
    {
        return [
            'getVariants' => ['AddToCartItem'],
            'getDiscr'    => ['AddToCartItem'],
        ];
    }

    /**
     * @param ConfiguratorItemAdditionalProduct $item
     *
     * @return ArrayCollection
     */
    public function getVariants(ConfiguratorItemAdditionalProduct $item): Collection
    {
        return $item->getProduct()->getVariants();
    }

    /**
     * @param ConfiguratorItemAdditionalProduct $item
     *
     * @return string
     */
    public function getDiscr(ConfiguratorItemAdditionalProduct $item): string
    {
        return 'product_additional';
    }
}
