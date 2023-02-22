<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Factory\Model;

use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Core\Factory\CartItemFactoryInterface as BaseCartItemFactoryInterface;
/**
 * Class OrderItemFactoryInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Factory\Model
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface CartItemFactoryInterface extends BaseCartItemFactoryInterface
{
    public function createForConfigurator(ConfiguratorInterface $configurator): OrderItemInterface;
}
