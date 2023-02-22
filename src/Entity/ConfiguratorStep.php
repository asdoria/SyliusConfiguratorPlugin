<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Entity;

use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorStepImageInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorStepInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorStepTranslationInterface;
use Asdoria\SyliusConfiguratorPlugin\Traits\CodeTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\ConfiguratorItemsTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\ImageTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\ResourceTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\SortableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;

/**
 * Class ConfiguratorStep
 * @package Asdoria\SyliusConfiguratorPlugin\Entity
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorStep implements ConfiguratorStepInterface
{
    use ResourceTrait;
    use CodeTrait;
    use ImageTrait;
    use TranslatableTrait {
        TranslatableTrait::__construct as private initializeTranslationsCollection;
        TranslatableTrait::getTranslation as private doGetTranslation;
    }


    protected int $position = 0;
    public function __construct()
    {
        $this->initializeTranslationsCollection();
    }

    public function __toString()
    {
        return $this->getFullname();
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
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->getTranslation()->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getFullname(): ?string
    {
       return $this->getName();
    }

    /**
     * @return ConfiguratorStepTranslationInterface
     */
    protected function createTranslation(): ConfiguratorStepTranslationInterface
    {
        return new ConfiguratorStepTranslation();
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return null|string
     */
    public function getPath(): ?string
    {
        if (!$this->getImage() instanceof ConfiguratorStepImageInterface) {
            return null;
        }

        return $this->getImage()->getPath();
    }
}
