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

class FlatRateCalculator implements CalculatorInterface
{
    public function calculate(ConfiguratorAwareInterface $subject, array $configuration): int|float
    {
        $channelCode = $subject?->getOrder()?->getChannel()?->getCode();
        ['price' => $price] = $configuration;
        return ((int) $price + (int) $configuration[$channelCode]['amount']);
    }

    public function getType(): string
    {
        return 'flat_rate';
    }
}
