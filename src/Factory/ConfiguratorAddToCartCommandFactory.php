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

namespace Asdoria\SyliusConfiguratorPlugin\Factory;

use Asdoria\SyliusConfiguratorPlugin\Context\Model\ConfiguratorContextInterface;
use Asdoria\SyliusConfiguratorPlugin\Controller\Shop\ConfiguratorAddToCartCommand;
use Asdoria\SyliusConfiguratorPlugin\Controller\Shop\ConfiguratorAddToCartCommandInterface;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorItemProductAttribute;
use Asdoria\SyliusConfiguratorPlugin\Factory\Model\ConfiguratorAddToCartCommandFactoryInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\AttributeValuesAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorItemAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorStepInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\OrderItemAttributeValueInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Bundle\OrderBundle\Controller\AddToCartCommandInterface;
use Sylius\Bundle\OrderBundle\Factory\AddToCartCommandFactoryInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ConfiguratorAddToCartCommandFactory
 * @package Asdoria\SyliusConfiguratorPlugin\Factory
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
final class ConfiguratorAddToCartCommandFactory implements ConfiguratorAddToCartCommandFactoryInterface
{
    public function __construct(
        protected CartContextInterface               $cartContext,
        protected ConfiguratorContextInterface       $configuratorContext,
        protected FactoryInterface                   $orderItemFactory,
        protected AddToCartCommandFactoryInterface   $addToCartCommandFactory,
        protected OrderItemQuantityModifierInterface $orderItemQuantityModifier,
        protected FactoryInterface                   $orderItemAttributeValueFactory
    )
    {
    }

    public function createWithAddToCartItems(): ConfiguratorAddToCartCommandInterface
    {
        $cart            = $this->cartContext->getCart();
        $configurator    = $this->configuratorContext->getConfigurator();
        $additionalItems = new ArrayCollection();

        if (!$configurator instanceof ConfiguratorInterface) {
            throw new NotFoundHttpException('The configurator has not been found');
        }

        foreach ($configurator->getAdditionalProducts() as $item) {
            $orderItem = $this->orderItemFactory->createNew();
            $orderItem->setConfigurator($configurator);
            $this->orderItemQuantityModifier->modify($orderItem, 1);
            $addToCartCommand = $this->addToCartCommandFactory->createWithCartAndCartItem($cart, $orderItem);
            if ($addToCartCommand instanceof ConfiguratorItemAwareInterface) {
                $addToCartCommand->setConfiguratorItem($item);
            }
            $additionalItems->add($addToCartCommand);
        }

        return new ConfiguratorAddToCartCommand($cart, $this->createOrderItem($cart, $configurator), $additionalItems);
    }

    protected function createOrderItem(
        OrderInterface        $cart,
        ConfiguratorInterface $configurator
    ): OrderItemInterface
    {
        $orderItem = $this->orderItemFactory->createNew();
        $this->orderItemQuantityModifier->modify($orderItem, 1);
        $orderItem->setConfigurator($configurator);
        $items = $configurator->getProductAttributes();
        /** @var ConfiguratorItemProductAttribute $configuratorItem */
        foreach ($items as $configuratorItem) {
            /** @var OrderItemAttributeValueInterface $attrValue */
            $attrValue = $this->orderItemAttributeValueFactory->createNew();
            $attrValue->setAttribute($configuratorItem->getProductAttribute());
            $attrValue->setLocaleCode($configurator->getTranslation()->getLocale());
            $orderItem->addAttribute($attrValue);
        }

        return $orderItem;
    }
}
