<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Calculator;

use Asdoria\SyliusConfiguratorPlugin\Calculator\Model\CalculatorInterface;
use Asdoria\SyliusConfiguratorPlugin\Calculator\Model\DelegatingCalculatorInterface;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorItemProductAttribute;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\AttributeValuesAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorAwareInterface;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

/**
 * Class DefaultConfiguratorCalculator
 * @package Asdoria\SyliusConfiguratorPlugin\Calculator
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class DefaultConfiguratorCalculator implements CalculatorInterface
{

    public function __construct(private ServiceRegistryInterface $registry)
    {
    }

    public function calculate(ConfiguratorAwareInterface $subject, array $configuration): int|float
    {
        if (!$subject instanceof AttributeValuesAwareInterface) return 0;

        $configurator = $subject->getConfigurator();

        $basePrice = 0;
        if (method_exists($subject, 'getUnitPrice')) {
            $basePrice = $subject->getUnitPrice();
        }


        /** @var ConfiguratorItemProductAttribute $item */
        foreach ($configurator->getProductAttributes() as $item) {
            $value = $configurator
                ->getProductAttributeValuesWithStep($subject, $item->getConfiguratorStep())
                ->filter(fn(?AttributeValueInterface $attributeValue) => $attributeValue?->getAttribute() === $item->getProductAttribute())
                ->first();

            if (
                !$value instanceof AttributeValueInterface ||
                empty($value->getValue()) ||
                empty($item->getCalculator())
            ) continue;

            /** @var CalculatorInterface $calculator */
            $calculator = $this->registry->get($item->getCalculator());
            if (!$calculator instanceof CalculatorInterface) continue;
            $basePrice = $calculator->calculate(
                $subject,
                array_merge($item->getConfiguration(), ['value'=> $value, 'price' => $basePrice]));
        }

        /** @var OrderItemInterface $subject */
        return $basePrice;
    }

    public function getType(): string
    {
        return 'default_configurator';
    }
}
