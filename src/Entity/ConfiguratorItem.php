<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Entity;


use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemTranslationInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorTranslationInterface;
use Asdoria\SyliusConfiguratorPlugin\Traits\CodeTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\ConfiguratorStepTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\ConfiguratorTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\ImagesTrait;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * Class ConfiguratorItem
 * @package Asdoria\SyliusConfiguratorPlugin\Entity
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
abstract class ConfiguratorItem implements ConfiguratorItemInterface
{
    use TimestampableTrait;
    use ToggleableTrait;
    use ImagesTrait;
    use ConfiguratorTrait;
    use ConfiguratorStepTrait;
    use CodeTrait;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
        getTranslation as private doGetTranslation;
    }
    /**
     * @var int
     */
    protected $id;

    protected int $position = 0;

    /** @var mixed[] */
    protected array $configuration = [];

    public function __construct()
    {
        $this->initializeTranslationsCollection();
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

    public function getDescription(): ?string
    {
        return $this->getTranslation()->getDescription();
    }

    public function setDescription(?string $description): void
    {
        $this->getTranslation()->setDescription($description);
    }

    /**
     * @return mixed[]
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    /**
     * @param mixed[] $configuration
     */
    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }


    protected function createTranslation(): ConfiguratorItemTranslationInterface
    {
        return new ConfiguratorItemTranslation();
    }

    /**
     * @return ConfiguratorItemTranslationInterface
     */
    public function getTranslation(?string $locale = null): TranslationInterface
    {
        /** @var ConfiguratorItemTranslationInterface $translation */
        $translation = $this->doGetTranslation($locale);

        return $translation;
    }
}
