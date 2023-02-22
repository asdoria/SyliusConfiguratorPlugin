<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits\OrderItem;

use Asdoria\SyliusConfiguratorPlugin\Model\OrderItemAttributeValueInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Order\Model\OrderItemInterface as BaseOrderItemInterface;
use Sylius\Component\Product\Model\ProductAttributeValueInterface;
use Webmozart\Assert\Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class AttributeValueTrait
 *
 * @author Philippe Vesin <pve.asdoria@gmail.com>
 */
trait AttributeValuesTrait
{
    /**
     * @ORM\OneToMany(
     *      targetEntity="Asdoria\SyliusConfiguratorPlugin\Model\OrderItemAttributeValueInterface",
     *      mappedBy="subject",
     *      cascade={"all"})
     * @var OrderItemAttributeValueInterface[]|Collection
     */
    protected Collection $attributes;

    protected function initializeAttributeValues(): void
    {
        $this->attributes = new ArrayCollection();
    }

    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function getAttributesByLocale(
        string $localeCode,
        string $fallbackLocaleCode,
        ?string $baseLocaleCode = null
    ): Collection {
        if (null === $baseLocaleCode || $baseLocaleCode === $fallbackLocaleCode) {
            $baseLocaleCode = $fallbackLocaleCode;
            $fallbackLocaleCode = null;
        }

        $attributes = $this->attributes->filter(
            function (OrderItemAttributeValueInterface $attribute) use ($baseLocaleCode) {
                return $attribute->getLocaleCode() === $baseLocaleCode || null === $attribute->getLocaleCode();
            }
        );

        $attributesWithFallback = [];
        foreach ($attributes as $attribute) {
            $attributesWithFallback[] = $this->getAttributeInDifferentLocale($attribute, $localeCode, $fallbackLocaleCode);
        }

        return new ArrayCollection($attributesWithFallback);
    }

    protected function getAttributeInDifferentLocale(
        ProductAttributeValueInterface $attributeValue,
        string $localeCode,
        ?string $fallbackLocaleCode = null
    ): AttributeValueInterface {
        if (!$this->hasNotEmptyAttributeByCodeAndLocale($attributeValue->getCode(), $localeCode)) {
            if (
                null !== $fallbackLocaleCode &&
                $this->hasNotEmptyAttributeByCodeAndLocale($attributeValue->getCode(), $fallbackLocaleCode)
            ) {
                return $this->getAttributeByCodeAndLocale($attributeValue->getCode(), $fallbackLocaleCode);
            }

            return $attributeValue;
        }

        return $this->getAttributeByCodeAndLocale($attributeValue->getCode(), $localeCode);
    }

    protected function hasNotEmptyAttributeByCodeAndLocale(string $attributeCode, string $localeCode): bool
    {
        $attributeValue = $this->getAttributeByCodeAndLocale($attributeCode, $localeCode);
        if (null === $attributeValue) {
            return false;
        }

        $value = $attributeValue->getValue();
        if ('' === $value || null === $value || [] === $value) {
            return false;
        }

        return true;
    }

    public function addAttribute(?AttributeValueInterface $attribute): void
    {
        /** @var OrderItemAttributeValueInterface $attribute */
        Assert::isInstanceOf(
            $attribute,
            OrderItemAttributeValueInterface::class,
            'Attribute objects added to a Product object have to implement OrderItemAttributeValueInterface'
        );

        if (!$this->hasAttribute($attribute)) {
            $attribute->setOrderItem($this);
            $this->attributes->add($attribute);
        }
    }

    public function removeAttribute(?AttributeValueInterface $attribute): void
    {
        /** @var OrderItemAttributeValueInterface $attribute */
        Assert::isInstanceOf(
            $attribute,
            OrderItemAttributeValueInterface::class,
            'Attribute objects removed from a Product object have to implement OrderItemAttributeValueInterface'
        );

        if ($this->hasAttribute($attribute)) {
            $this->attributes->removeElement($attribute);
            $attribute->setOrderItem(null);
        }
    }

    public function hasAttribute(AttributeValueInterface $attribute): bool
    {
        return $this->attributes->contains($attribute);
    }

    public function hasAttributeByCodeAndLocale(string $attributeCode, ?string $localeCode = null): bool
    {
        $localeCode = $localeCode ?: $this->getTranslation()->getLocale();

        foreach ($this->attributes as $attribute) {
            if ($attribute->getAttribute()->getCode() === $attributeCode
                && ($attribute->getLocaleCode() === $localeCode || null === $attribute->getLocaleCode())) {
                return true;
            }
        }

        return false;
    }

    public function getAttributeByCodeAndLocale(string $attributeCode, ?string $localeCode = null): ?AttributeValueInterface
    {
        if (null === $localeCode) {
            $localeCode = $this->getTranslation()->getLocale();
        }

        foreach ($this->attributes as $attribute) {
            if ($attribute->getAttribute()->getCode() === $attributeCode &&
                ($attribute->getLocaleCode() === $localeCode || null === $attribute->getLocaleCode())) {
                return $attribute;
            }
        }

        return null;
    }

    public function equals(BaseOrderItemInterface $orderItem): bool
    {
        if ($orderItem->getAttributes()->isEmpty()) {
            return parent::equals($orderItem);
        }
        return false;
    }
}
