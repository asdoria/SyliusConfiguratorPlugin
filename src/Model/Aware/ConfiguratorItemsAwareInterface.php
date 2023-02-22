<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Model\Aware;

use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemInterface as ItemInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class ConfiguratorItemsAwareInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Model\Aware
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface ConfiguratorItemsAwareInterface
{
    public function initializeConfiguratorItemsCollection();

    /**
     * {@inheritdoc}
     */
    public function getItems(): Collection;

    /**
     * {@inheritdoc}
     */
    public function getItemsByType(string $type): Collection;

    /**
     * {@inheritdoc}
     */
    public function hasItems(): bool;

    /**
     * {@inheritdoc}
     */
    public function hasItem(ItemInterface $item): bool;
    /**
     * {@inheritdoc}
     */
    public function addItem(ItemInterface $item): void;

    /**
     * {@inheritdoc}
     */
    public function removeItem(ItemInterface $item): void;

}
