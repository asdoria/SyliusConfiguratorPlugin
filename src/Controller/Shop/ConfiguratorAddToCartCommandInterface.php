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

use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorStepInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;

interface ConfiguratorAddToCartCommandInterface
{
    public function getCartItem(): OrderItemInterface;
    public function getAddToCartCommandAdditionalItems(): ArrayCollection;
    public function getCart(): OrderInterface;
    public function getStep(): ConfiguratorStepInterface;
    public function setStep(ConfiguratorStepInterface $step): void;
    public function nextStep(ConfiguratorAddToCartCommandInterface $configuratorAddToCartCommand): void;
    public function getConfigurator(): ConfiguratorInterface;
    public function handleFormData(array $formData = []): void;
}
