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
use Asdoria\SyliusConfiguratorPlugin\Calculator\Model\DelegatingCalculatorInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorAwareInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

/**
 * Class DelegatingCalculator
 * @package Asdoria\SyliusConfiguratorPlugin\Calculator
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class DelegatingCalculator implements DelegatingCalculatorInterface
{
    public function __construct(private ServiceRegistryInterface $registry)
    {
    }

    public function calculate(ConfiguratorAwareInterface $subject): int
    {
        if (null === $configurator = $subject->getConfigurator()) {
            throw new UndefinedConfiguratorException('Cannot calculate charge for calculator without a defined configurator.');
        }

        /** @var CalculatorInterface $calculator */
        $calculator = $this->registry->get($configurator->getCalculator());

        return $calculator->calculate($subject, $configurator->getConfiguration());
    }
}
