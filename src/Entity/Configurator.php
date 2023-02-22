<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Entity;


use Asdoria\SyliusConfiguratorPlugin\Model\Aware\AttributeValuesAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorStepAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorStepInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorTranslationInterface;
use Asdoria\SyliusConfiguratorPlugin\Traits\ChannelsTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\ConfiguratorItemsTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\ConfiguratorStepTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\ImagesTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\ProductsTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Model\ArchivableTrait;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;
use Sylius\Component\Shipping\Model\ShippingMethodRuleInterface;

/**
 * Class Configurator
 * @package Asdoria\SyliusConfiguratorPlugin\Entity
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class Configurator implements ConfiguratorInterface
{
    use ArchivableTrait;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
        getTranslation as private doGetTranslation;
    }
    use TimestampableTrait;
    use ChannelsTrait;
    use ToggleableTrait;
    use ImagesTrait;
    use ProductsTrait;
    use ConfiguratorItemsTrait;
    use ConfiguratorStepTrait;

    /**
     * @var int
     */
    protected $id;

    protected int $position = 0;

    /** @var string|null */
    protected ?string $calculator = null;

    /** @var mixed[] */
    protected $configuration = [];


    /**
     * Configurator constructor.
     */
    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->initializeImagesCollection();
        $this->initializeChannelsCollection();
        $this->initializeProductsCollection();
        $this->initializeConfiguratorItemsCollection();
    }

    /**
     * @return string|null
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }


    /**
     * @return number
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getName(): ?string
    {
        return $this->getTranslation()->getName();
    }

    public function setName(?string $name): void
    {
        $this->getTranslation()->setName($name);
    }

    public function getSlug(): ?string
    {
        return $this->getTranslation()->getSlug();
    }

    public function setSlug(?string $slug): void
    {
        $this->getTranslation()->setSlug($slug);
    }

    public function getDescription(): ?string
    {
        return $this->getTranslation()->getDescription();
    }

    public function setDescription(?string $description): void
    {
        $this->getTranslation()->setDescription($description);
    }

    /**
     * @return ConfiguratorTranslationInterface
     */
    public function getTranslation(?string $locale = null): TranslationInterface
    {
        /** @var ConfiguratorTranslationInterface $translation */
        $translation = $this->doGetTranslation($locale);

        return $translation;
    }


    protected function createTranslation(): ConfiguratorTranslationInterface
    {
        return new ConfiguratorTranslation();
    }


    /***
     * @return Collection
     */
    public function getProductAttributes(): Collection
    {
        $criteria = Criteria::create()
            ->orderBy(["position" => Criteria::ASC]);

        return $this->items
            ->filter(fn(ConfiguratorItemInterface $item) => $item instanceof ConfiguratorItemProductAttribute)
            ->matching($criteria);
    }

    /***
     * @return Collection
     */
    public function getAdditionalProducts(): Collection
    {
        return $this->items->filter(fn(ConfiguratorItemInterface $item) => $item instanceof ConfiguratorItemAdditionalProduct);
    }

    /***
     * @param ConfiguratorStepInterface $step
     *
     * @return ArrayCollection
     */
    public function getItemsByStep(
        ConfiguratorStepInterface $step
    ): ArrayCollection
    {
        return $this->getItems()->filter(
            fn(ConfiguratorItemInterface $item) =>
                $item->getConfiguratorStep() === $step
        );
    }

    /***
     * @return Collection
     */
    public function getItemProductAttributeByStepAndAttribute(
        ConfiguratorStepInterface $step, AttributeInterface $attribute
    ): ?ConfiguratorItemProductAttribute
    {
        $items =  $this->getProductAttributes()->filter(
            fn(ConfiguratorItemProductAttribute $item) =>
                $item->getProductAttribute() === $attribute && $item->getConfiguratorStep() === $step
        );

        return $items->isEmpty() ? null : $items->first();
    }

    /***
     * @return Collection
     */
    public function getConfiguratorSteps(): Collection
    {
        $steps = new ArrayCollection([$this->getConfiguratorStep()]);

        $criteria = Criteria::create()
            ->orderBy(["position" => Criteria::ASC]);
        $rows = $this->items->matching($criteria);
        /** @var ConfiguratorItemInterface $item */
        foreach ($rows as $item) {
            $step = $item->getConfiguratorStep();
            if (!$steps->contains($step)) {
                $step->setPosition($steps->count());
                $steps->add($step);
            }
        }

        return $steps ;
    }

    public function getActiveSteps(ConfiguratorStepInterface $currentStep): Collection {
        $steps = new ArrayCollection();
        foreach ($this->getConfiguratorSteps() as $step) {
            $steps->add($step);
            if ($step === $currentStep) {
                break;
            }
        }
        return $steps;
    }

    public function getNextSteps(ConfiguratorStepInterface $currentStep): Collection {
        $steps = $this->getActiveSteps($currentStep);
        return $this->getConfiguratorSteps()
            ->filter(fn(ConfiguratorStepInterface $step) => !$steps->contains($step));
    }

    /**
     * @param AttributeValuesAwareInterface $owner
     *
     * @return Collection
     */
    public function getProductAttributeValuesWithStep(AttributeValuesAwareInterface $owner): Collection {
        $attributeValues = new ArrayCollection();
        foreach ($this->getProductAttributes() as $item) {
            $value = $owner->getAttributeByCodeAndLocale(
                $item->getProductAttribute()->getCode(),
                $this->getTranslation()->getLocale()
            );
            if ($value instanceof ConfiguratorStepAwareInterface) {
                $value->setConfiguratorStep($item->getConfiguratorStep());
            }
            $attributeValues->add($value);
        }

        return $attributeValues;
    }

    public function getVariants(): ArrayCollection {
        $variants = new ArrayCollection();
        foreach ($this->getProducts() as $product) {
            foreach ($product->getEnabledVariants() as $variant) {
                $variants->add($variant);
            }
        }
        return $variants;
    }

    public function getCalculator(): ?string
    {
        return $this->calculator;
    }

    public function setCalculator(?string $calculator): void
    {
        $this->calculator = $calculator;
    }

    public function getConfiguration(): array
    {
        return is_array($this->configuration)? $this->configuration : [];
    }

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function hasOnlySimpleProducts(): bool {
        return $this->getProducts()
            ->filter(fn(ProductInterface $product) => !$product->isSimple())->isEmpty();
    }
}
