<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Entity;

use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ProductAttributeAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemTranslationInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorTranslationInterface;
use Asdoria\SyliusConfiguratorPlugin\Traits\ProductAttributeTrait;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * Class ConfiguratorItemProductAttribute
 * @package Asdoria\SyliusConfiguratorPlugin\Entity
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorItemProductAttribute
    extends ConfiguratorItem
    implements ProductAttributeAwareInterface
{
    use ProductAttributeTrait;

    /** @var string|null */
    protected ?string $calculator = null;

    /**
     * Configurator constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->initializeImagesCollection();
    }
    /**
     * @return bool
     */
    public function isMultiple(): bool
    {
        if (empty($this->configuration['multiple'])) {
            $this->configuration['multiple'] = false;
        }

        return (bool)$this->configuration['multiple'];
    }

    /**
     * @param bool $multiple
     */
    public function setMultiple($multiple): void
    {
        $this->configuration['multiple'] = $multiple;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        if (empty($this->configuration['required'])) {
            $this->configuration['required'] = false;
        }

        return (bool)$this->configuration['required'];
    }

    /**
     * @param bool $required
     */
    public function setRequired($required): void
    {
        $this->configuration['required'] = $required;
    }


    public function getCalculator(): ?string
    {
        return $this->calculator;
    }

    public function setCalculator(?string $calculator): void
    {
        $this->calculator = $calculator;
    }
}
