<?php

declare(strict_types=1);


namespace Asdoria\SyliusConfiguratorPlugin\Factory;


use Asdoria\SyliusConfiguratorPlugin\Controller\Shop\AddToCartCommand;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemInterface;
use Sylius\Bundle\OrderBundle\Controller\AddToCartCommandInterface;
use Sylius\Bundle\OrderBundle\Factory\AddToCartCommandFactoryInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Model\OrderItemInterface;

/**
 * Class AddToCartCommandFactory
 * @package Asdoria\SyliusConfiguratorPlugin\Factory
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class AddToCartCommandFactory implements AddToCartCommandFactoryInterface
{
    public function createWithCartAndCartItem(OrderInterface $cart, OrderItemInterface $cartItem): AddToCartCommandInterface
    {
        return new AddToCartCommand($cart, $cartItem);
    }
}
