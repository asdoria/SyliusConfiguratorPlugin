<?php

declare(strict_types=1);


namespace Asdoria\SyliusConfiguratorPlugin\Controller\Shop;


use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorItemAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemInterface;
use Asdoria\SyliusConfiguratorPlugin\Traits\ConfiguratorItemTrait;
use Sylius\Bundle\OrderBundle\Controller\AddToCartCommandInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Model\OrderItemInterface;

/**
 * Class AddToCartCommand
 * @package Asdoria\SyliusConfiguratorPlugin\Controller\Shop
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class AddToCartCommand implements AddToCartCommandInterface, ConfiguratorItemAwareInterface
{
    use ConfiguratorItemTrait;
    public function __construct(
        private OrderInterface $cart,
        public OrderItemInterface $cartItem
    )
    {
    }

    public function getCart(): OrderInterface
    {
        return $this->cart;
    }

    public function getCartItem(): OrderItemInterface
    {
        return $this->cartItem;
    }
}
