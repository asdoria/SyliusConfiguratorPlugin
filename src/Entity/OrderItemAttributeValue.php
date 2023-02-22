<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Entity;

use Asdoria\SyliusConfiguratorPlugin\Model\OrderItemAttributeValueInterface;
use Asdoria\SyliusConfiguratorPlugin\Traits\ConfiguratorStepTrait;
use Sylius\Component\Attribute\Model\AttributeValue as BaseAttributeValue;
use Sylius\Component\Core\Model\OrderItemInterface;
use Webmozart\Assert\Assert;


/**
 * Class OrderItemAttributeValue
 * @package Asdoria\SyliusConfiguratorPlugin\Entity
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class OrderItemAttributeValue extends BaseAttributeValue implements OrderItemAttributeValueInterface
{
    use ConfiguratorStepTrait;
    public function getOrderItem(): ?OrderItemInterface
    {
        $subject = parent::getSubject();

        /** @var OrderItemInterface|null $subject */
        Assert::nullOrIsInstanceOf($subject, OrderItemInterface::class);

        return $subject;
    }

    public function setOrderItem(?OrderItemInterface $orderItem): void
    {
        parent::setSubject($orderItem);
    }
}
