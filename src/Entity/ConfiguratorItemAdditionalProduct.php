<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Entity;

use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ProductAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemTranslationInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorTranslationInterface;
use Asdoria\SyliusConfiguratorPlugin\Traits\ProductTrait;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * Class ConfiguratorItemProductAttribute
 * @package Asdoria\SyliusConfiguratorPlugin\Entity
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorItemAdditionalProduct
    extends ConfiguratorItem
    implements ProductAwareInterface
{
    use ProductTrait;

    /**
     * Configurator constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->initializeImagesCollection();
    }
}
