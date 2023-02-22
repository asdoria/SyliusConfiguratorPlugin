<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits;

use Asdoria\SyliusConfiguratorPlugin\Calculator\Model\DelegatingCalculatorInterface;

/**
 * Class ConfiguratorCalculatorTrait
 * @package Asdoria\SyliusConfiguratorPlugin\Traits
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
trait ConfiguratorCalculatorTrait
{
    protected ?DelegatingCalculatorInterface $configuratorCalculator = null;

    /**
     * @return DelegatingCalculatorInterface|null
     */
    public function getConfiguratorCalculator(): ?DelegatingCalculatorInterface
    {
        return $this->configuratorCalculator;
    }

    /**
     * @param DelegatingCalculatorInterface|null $configuratorCalculator
     */
    public function setConfiguratorCalculator(?DelegatingCalculatorInterface $configuratorCalculator): void
    {
        $this->configuratorCalculator = $configuratorCalculator;
    }
}
