<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits;

use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;
use Sylius\Component\Currency\Context\CurrencyContextInterface;

/**
 * Class CurrencyContextTrait
 * @package Asdoria\SyliusConfiguratorPlugin\Traits
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
trait CurrencyContextTrait
{
    protected CurrencyContextInterface $currencyContext;

    /**
     * @return CurrencyContextInterface
     */
    public function getCurrencyContext(): CurrencyContextInterface
    {
        return $this->currencyContext;
    }

    /**
     * @param CurrencyContextInterface $currencyContext
     */
    public function setCurrencyContext(CurrencyContextInterface $currencyContext): void
    {
        $this->currencyContext = $currencyContext;
    }
}
