<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits;

use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;

/**
 * Class MoneyFormatterTrait
 * @package Asdoria\SyliusConfiguratorPlugin\Traits
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
trait MoneyFormatterTrait
{

    protected MoneyFormatterInterface $moneyFormatter;

    /**
     * @return MoneyFormatterInterface
     */
    public function getMoneyFormatter(): MoneyFormatterInterface
    {
        return $this->moneyFormatter;
    }

    /**
     * @param MoneyFormatterInterface $moneyFormatter
     */
    public function setMoneyFormatter(MoneyFormatterInterface $moneyFormatter): void
    {
        $this->moneyFormatter = $moneyFormatter;
    }
}
