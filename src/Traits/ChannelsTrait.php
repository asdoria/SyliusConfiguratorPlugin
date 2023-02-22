<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\ChannelInterface;

/**
 * Trait ChannelsTrait
 * @package Asdoria\SyliusConfiguratorPlugin\Traits
 *
 * @author  Hugo Duval <hugo.duval@asdoria.com>
 */
trait ChannelsTrait
{
    /**
     * @var Collection|ChannelInterface[]
     */
    protected Collection $channels;
    
    public function initializeChannelsCollection(): void
    {
        $this->channels = new ArrayCollection();
    }

    /**
     * @return Collection|ChannelInterface[]
     */
    public function getChannels(): Collection
    {
        return $this->channels;
    }

    /**
     * @param ChannelInterface $channel
     */
    public function addChannel(ChannelInterface $channel): void
    {
        if (!$this->hasChannel($channel)) {
            $this->channels->add($channel);
        }
    }

    /**
     * @param ChannelInterface $channel
     */
    public function removeChannel(ChannelInterface $channel): void
    {
        if ($this->hasChannel($channel)) {
            $this->channels->removeElement($channel);
        }
    }

    /**
     * @param ChannelInterface $channel
     *
     * @return bool
     */
    public function hasChannel(ChannelInterface $channel): bool
    {
        return $this->channels->contains($channel);
    }

    /**
     * @return bool
     */
    public function hasChannels(): bool
    {
        return $this->channels->isEmpty();
    }
}
