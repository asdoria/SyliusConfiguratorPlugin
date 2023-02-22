<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits;

use Sylius\Component\Registry\ServiceRegistry;

/**
 * Class CalculatorRegistryTrait
 * @package Asdoria\SyliusConfiguratorPlugin\Traits
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
trait CalculatorRegistryTrait
{
    protected ?ServiceRegistry $calculatorRegistry = null;

    /**
     * @return ServiceRegistry|null
     */
    public function getCalculatorRegistry(): ?ServiceRegistry
    {
        return $this->calculatorRegistry;
    }

    /**
     * @param ServiceRegistry|null $calculatorRegistry
     */
    public function setCalculatorRegistry(?ServiceRegistry $calculatorRegistry): void
    {
        $this->calculatorRegistry = $calculatorRegistry;
    }
    
    
}
