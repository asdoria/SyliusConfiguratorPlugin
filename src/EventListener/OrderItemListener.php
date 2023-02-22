<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\RequestInterface;
use Sylius\Bundle\ResourceBundle\Controller\FlashHelperInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Order\CartActions;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Modifier\OrderModifierInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class OrderItemListener
 * @package Asdoria\SyliusConfiguratorPlugin\EventListener
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class OrderItemListener
{

    /**
     * @param CartContextInterface   $cartContext
     * @param OrderModifierInterface $orderModifier
     * @param EntityManagerInterface $cartManager
     * @param FlashHelperInterface   $flashHelper
     */
    public function __construct(
        protected CartContextInterface                 $cartContext,
        protected OrderModifierInterface               $orderModifier,
        protected EntityManagerInterface               $cartManager,
        protected FlashHelperInterface                 $flashHelper,
        protected RegistryInterface                    $metadataRegistry,
        protected RequestStack                         $requestStack,
        protected RequestConfigurationFactoryInterface $requestConfigurationFactory,
    )
    {

    }

    /**
     * @param ResourceControllerEvent $controllerEvent
     *
     * @return void
     */
    public function addToCart(ResourceControllerEvent $controllerEvent): void
    {
        $cartItem = $controllerEvent->getSubject();
        if (!$cartItem instanceof OrderItemInterface) return;

        $cart = $this->cartContext->getCart();
        $cartItem->setOrder($cart);
        $this->orderModifier->addToOrder($cart, $cartItem);
        $this->cartManager->persist($cart);
        $this->cartManager->flush();

        $mainRequest   = $this->requestStack->getMainRequest();
        $metadata      = $this->metadataRegistry->get('sylius.order_item');
        $configuration = $this->requestConfigurationFactory->create($metadata, $mainRequest);

        $this->flashHelper->addSuccessFlash($configuration, CartActions::ADD, $cartItem);
    }
}
