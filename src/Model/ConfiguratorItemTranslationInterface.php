<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * Class ConfiguratorItemTranslationInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Model
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface ConfiguratorItemTranslationInterface extends
    ResourceInterface,
    TranslationInterface
{
    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getDescription(): ?string;

    public function setDescription(?string $description): void;
}
