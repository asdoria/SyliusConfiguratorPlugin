<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Model;

use Asdoria\SyliusConfiguratorPlugin\Model\Aware\NamingAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * Interface ConfiguratorStepTranslationInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Model
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface ConfiguratorStepTranslationInterface extends
    ResourceInterface,
    TranslationInterface,
    NamingAwareInterface
{
    public function getDescription(): ?string;

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void;
}

