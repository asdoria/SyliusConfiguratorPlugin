<?php

declare(strict_types=1);


namespace Asdoria\SyliusConfiguratorPlugin\Factory\Model;


use Asdoria\SyliusConfiguratorPlugin\Controller\Shop\ConfiguratorAddToCartCommandInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorStepInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Bundle\OrderBundle\Controller\AddToCartCommandInterface;
use Sylius\Component\Order\Model\OrderInterface;


/**
 * Class ConfiguratorAddToCartCommandFactoryInterface
 *
 * @author Philippe Vesin <pve.asdoria@gmail.com>
 */
interface ConfiguratorAddToCartCommandFactoryInterface
{
    public function createWithAddToCartItems(): ConfiguratorAddToCartCommandInterface;
}
