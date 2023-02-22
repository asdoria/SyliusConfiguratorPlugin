<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Model;

use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorItemAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorItemsAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\SortableAwareInterface;
use Sylius\Component\Core\Model\ImageAwareInterface;
use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

/**
 * Class ConfiguratorStepInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Model
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface ConfiguratorStepInterface extends
    ResourceInterface,
    TranslatableInterface,
    ImageAwareInterface,
    CodeAwareInterface
{
    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * {@inheritdoc}
     */
    public function getFullname(): ?string;
    public function getDescription(): ?string;

    public function setDescription(?string $description): void;
    /**
     * @return number
     */
    public function getPosition(): int;
    public function setPosition(int $position): void;

    /**
     * @return null|string
     */
    public function getPath(): ?string;
}
