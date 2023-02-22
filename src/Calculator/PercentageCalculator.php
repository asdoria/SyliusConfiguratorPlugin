<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Calculator;

use Asdoria\SyliusConfiguratorPlugin\Calculator\Model\CalculatorInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorAwareInterface;

/**
 * Class PercentageCalculator
 * @package Asdoria\SyliusConfiguratorPlugin\Calculator
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class PercentageCalculator implements CalculatorInterface
{
    public function calculate(ConfiguratorAwareInterface $subject, array $configuration): int|float
    {
        if (!method_exists($subject, 'getUnitPrice')) {
            return (int) (floatval($configuration['percentage']) * 100);
        }

        return (int) ($subject->getUnitPrice() * floatval($configuration['percentage']));
    }

    public function getType(): string
    {
        return 'percentage';
    }
}
