<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Calculator\Model;

use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorAwareInterface;

/**
 * Interface CalculatorInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Calculator\Model
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface CalculatorInterface
{
    public function calculate(ConfiguratorAwareInterface $subject, array $configuration): int|float;
}
