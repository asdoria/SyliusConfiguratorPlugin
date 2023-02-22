<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Calculator;

use Asdoria\SyliusConfiguratorPlugin\Calculator\Model\CalculatorInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorAwareInterface;
use Sylius\Component\Attribute\Model\AttributeValueInterface;

class ProductAttributeChoicePercentageCalculator implements CalculatorInterface
{
    const _TYPE = 'product_attribute_choice_percentage';
    public function calculate(ConfiguratorAwareInterface $subject, array $configuration): int|float
    {
        $value = $configuration['value'] ?? null;
        if (!$value instanceof AttributeValueInterface) return 0;
        ['price' => $price] = $configuration;
        if(!is_array($value->getValue())) {
            return $price + ($price * $configuration[$value->getValue()]['percentage'] ?? 0);
        }

        foreach ($value->getValue()  as $value) {
            $price += ($price * $configuration[$value]['percentage'] ?? 0);
        }

        return (int) $price;
    }

    public function getType(): string
    {
        return self::_TYPE;
    }
}
