<?php

namespace Asdoria\SyliusConfiguratorPlugin\EventListener\Serializer;

use App\Entity\Product\ProductVariant;
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
use Sylius\Component\Product\Model\ProductAttributeValueInterface;

/**
 * Class ProductVariantEntitySubscriber
 * @package Asdoria\SyliusConfiguratorPlugin\EventListener\Serializer
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ProductVariantEntitySubscriber extends AbstractEntitySubscriber
{

    public function __construct(protected ConfiguratorContextInterface $configuratorContext) {

    }

    protected static function getClassName(): string
    {
        return ProductVariant::class;
    }

    protected function getMethodNames(): array
    {
        return [
            'getId'          => ['AddToCartItem'],
            'getCode'        => ['AddToCartItem'],
            'getProductName' => ['AddToCartItem'],
            'getPath'        => ['AddToCartItem'],
            'getAttributeValues'  => ['AddToCartItem'],
        ];
    }

    /**
     * @param ProductVariantInterface $variant
     *
     * @return string|null
     */
    public function getCode(ProductVariantInterface $variant): ?string
    {
        return $variant->getName();
    }

    /**
     * @param ProductVariantInterface $variant
     *
     * @return string|null
     */
    public function getId(ProductVariantInterface $variant): ?string
    {
        return $variant->getId();
    }

    /**
     * @param ProductVariantInterface $variant
     *
     * @return string|null
     */
    public function getProductName(ProductVariantInterface $variant): ?string
    {
        return $variant->getProduct()->getName();
    }

    /**
     * @param ProductVariantInterface $variant
     *
     * @return Collection
     */
    public function getAttributeValues(ProductVariantInterface $variant): array
    {
        $configurator = $this->configuratorContext->getConfigurator();
        $defaultLocale = $variant->getTranslation()->getLocale();

        $attributeValues = $variant
            ->getProduct()
            ->getAttributesByLocale(
                $defaultLocale, $defaultLocale
            );

        if (!$configurator instanceof ConfiguratorInterface) return $attributeValues->getValues();

        return $attributeValues->filter(fn(ProductAttributeValueInterface $attributeValue)
            => $configurator->getProductAttributes()->exists(
                fn($key, ConfiguratorItemProductAttribute $item)
                => $item->getProductAttribute() === $attributeValue->getAttribute()
            )
        )->getValues();
    }

    /**
     * @param ProductVariantInterface $variant
     *
     * @return string|null
     */
    public function getPath(ProductVariantInterface $variant): ?string
    {
        /** @var ProductInterface $product */
        $product = $variant->getProduct();
        if ($product->getImages()->isEmpty()) {
            return null;
        }

        $image =  $product->getImagesByType('thumnails')->first();

        if (!$image instanceof ImageInterface) {
            $image = $product->getImages()->first();
        }

        return $image->getPath();
    }
}
