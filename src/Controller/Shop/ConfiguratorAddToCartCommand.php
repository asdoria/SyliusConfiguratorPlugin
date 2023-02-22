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

namespace Asdoria\SyliusConfiguratorPlugin\Controller\Shop;

use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorItemAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorStepInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Sylius\Bundle\OrderBundle\Controller\AddToCartCommandInterface;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use function Clue\StreamFilter\fun;

class ConfiguratorAddToCartCommand implements ConfiguratorAddToCartCommandInterface
{
    public ConfiguratorStepInterface $step;
    public function __construct(
        public OrderInterface $cart,
        public OrderItemInterface $cartItem,
        public ArrayCollection $additionalItems
    )
    {
        /**  @var  ConfiguratorAwareInterface $cartItem */
        $this->setStep($cartItem?->getConfigurator()?->getConfiguratorStep());
    }

    public function getCartItem(): OrderItemInterface
    {
        return $this->cartItem;
    }

    public function getAddToCartCommandAdditionalItems(): ArrayCollection
    {
        return $this->additionalItems;
    }

    /**
     * @return OrderInterface
     */
    public function getCart(): OrderInterface
    {
        return $this->cart;
    }

    /**
     * @return ConfiguratorStepInterface
     */
    public function getStep(): ConfiguratorStepInterface
    {
        return $this->step;
    }

    /**
     * @param ConfiguratorStepInterface $step
     */
    public function setStep(ConfiguratorStepInterface $step): void
    {
        $this->step = $step;
    }

    /**
     * @return ConfiguratorInterface
     */
    public function getConfigurator(): ConfiguratorInterface
    {
        return $this->getCartItem()->getConfigurator();
    }

    /**
     * @return void
     */
    public function nextStep(ConfiguratorAddToCartCommandInterface $configuratorAddToCartCommand): void
    {
        $this->setStep($this->getConfigurator()->getNextSteps($this->getStep())->first());
        foreach ($configuratorAddToCartCommand->getCartItem()->getAttributes() as $attribute) {
            if (!$this->getCartItem()->getAttributes()->contains($attribute)) {
                $this->getCartItem()->getAttributes()->add($attribute);
            }
        }
        /** @var ConfiguratorItemAwareInterface $item */
        foreach ($configuratorAddToCartCommand->getAddToCartCommandAdditionalItems() as $item) {
            if (
                $this->getAddToCartCommandAdditionalItems()
                ->filter(fn(ConfiguratorItemAwareInterface $row) => $row->getConfiguratorItem() === $item->getConfiguratorItem())
                ->isEmpty()
            ) {
                $this->getAddToCartCommandAdditionalItems()->add($item);
            }
        }
    }

    /**
     * @param array $formData
     *
     * @return void
     */
    public function handleFormData(array $formData = []): void {
        $step = $formData['step'] ?? null;
        if (empty($step)) return;

        $criteria = Criteria::create()->where(Criteria::expr()->eq('id', (int)$step));
        $steps    = $this->getConfigurator()->getConfiguratorSteps()->matching($criteria);

        if($steps->isEmpty())  return;

        $this->setStep($steps->first());
    }

}
