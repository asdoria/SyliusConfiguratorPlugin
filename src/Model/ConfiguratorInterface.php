<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Model;

use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorItemProductAttribute;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\AdditionalProductsAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\AttributeValuesAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorStepAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorStepsAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ProductAttributesAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ProductsAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\SimilarConfiguratorsAwareInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Channel\Model\ChannelsAwareInterface;
use Sylius\Component\Core\Model\ImagesAwareInterface as BaseImagesAwareInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Model\ArchivableInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\SlugAwareInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

/**
 * Interface ConfiguratorInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Model
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface ConfiguratorInterface extends
    ResourceInterface,
    TimestampableInterface,
    ChannelsAwareInterface,
    ToggleableInterface,
    TranslatableInterface,
    SlugAwareInterface,
    ArchivableInterface,
    BaseImagesAwareInterface,
    ProductsAwareInterface,
    ConfiguratorStepAwareInterface
{

    /**
     * @return number
     */
    public function getPosition(): int;

    /**
     * @param int $position
     */
    public function setPosition(int $position): void;

    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getDescription(): ?string;

    public function setDescription(?string $description): void;

    public function getConfiguratorSteps(): Collection;
    public function getActiveSteps(ConfiguratorStepInterface $currentStep): Collection ;
    public function getNextSteps(ConfiguratorStepInterface $currentStep): Collection;
    public function getProductAttributeValuesWithStep(AttributeValuesAwareInterface $owner): Collection;
    public function getProductAttributes(): Collection;

    public function getAdditionalProducts(): Collection;
    public function getVariants(): ArrayCollection;
    public function getItemsByStep(
        ConfiguratorStepInterface $step
    ): ArrayCollection;
    public function getItemProductAttributeByStepAndAttribute(
        ConfiguratorStepInterface $step, AttributeInterface $attribute
    ): ?ConfiguratorItemProductAttribute;


    public function getCalculator(): ?string;

    public function setCalculator(?string $calculator): void;

    public function getConfiguration(): array;

    public function setConfiguration(array $configuration): void;

    public function hasOnlySimpleProducts(): bool;
}
