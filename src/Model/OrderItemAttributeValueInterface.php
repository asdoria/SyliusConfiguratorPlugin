<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Model;

use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorStepAwareInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Webmozart\Assert\Assert;
use Sylius\Component\Attribute\Model\AttributeValueInterface as BaseAttributeValueInterface;
/**
 * Class OrderItemAttributeValueInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Model
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface OrderItemAttributeValueInterface extends BaseAttributeValueInterface,ConfiguratorStepAwareInterface
{
    public function getOrderItem(): ?OrderItemInterface;


    public function setOrderItem(?OrderItemInterface $orderItem): void;
}
