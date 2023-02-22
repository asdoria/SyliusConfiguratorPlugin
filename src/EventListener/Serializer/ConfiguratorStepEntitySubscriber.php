<?php

namespace Asdoria\SyliusConfiguratorPlugin\EventListener\Serializer;

use App\Entity\Product\Product;
use App\Entity\Product\ProductAttribute;
use Asdoria\SyliusConfiguratorPlugin\Context\Model\ConfiguratorContextInterface;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorStep;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorStepInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Attribute\Model\Attribute;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Product\Model\ProductAttributeInterface;

/**
 * Class ConfiguratorStepEntitySubscriber
 * @package Asdoria\SyliusConfiguratorPlugin\EventListener\Serializer
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorStepEntitySubscriber extends AbstractEntitySubscriber
{

    public function __construct(protected ConfiguratorContextInterface $configuratorContext) {

    }

    protected static function getClassName(): string
    {
        return ConfiguratorStep::class;
    }

    protected function getMethodNames(): array
    {
        return [
            'getItems'  => ['AddToCartItem']
        ];
    }

    /**
     * @param ConfiguratorStep $step
     *
     * @return array
     */
    public function getItems(ConfiguratorStep $step): array
    {
        $configurator = $this->configuratorContext->getConfigurator();

        return $configurator->getItemsByStep($step)->getValues();
    }

}
