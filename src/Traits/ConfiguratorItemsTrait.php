<?php


declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemInterface as ItemInterface;

/**
 * Trait ConfiguratorItemsTrait
 * @package Asdoria\SyliusConfiguratorPlugin\Traits
 */
trait ConfiguratorItemsTrait
{
    protected ?Collection $items = null;

    public function initializeConfiguratorItemsCollection()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsByType(string $type): Collection
    {
        return $this->items->filter(function (ItemInterface $item) use ($type) {
            return $type === $item->getType();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function hasItems(): bool
    {
        return !$this->items->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function hasItem(ItemInterface $item): bool
    {
        return $this->items->contains($item);
    }

    /**
     * {@inheritdoc}
     */
    public function addItem(ItemInterface $item): void
    {
        if (false === $this->hasItem($item)) {
            if(method_exists($item, 'setOwner')) $item->setOwner($this);
            $this->items->add($item);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeItem(ItemInterface $item): void
    {
        if ($this->hasItem($item)) {
            if(method_exists($item, 'setOwner')) $item->setOwner(null);
            $this->getItems()->removeElement($item);
        }
    }

}
