<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits;

use Sylius\Component\Channel\Model\ChannelInterface;

/**
 *
 */
trait ChannelTrait
{
    /**
     * @var ChannelInterface|null
     */
    protected ?ChannelInterface $channel;

    /**
     * @return ChannelInterface|null
     */
    public function getChannel(): ?ChannelInterface
    {
        return $this->channel;
    }

    /**
     * @param ChannelInterface|null $channel
     */
    public function setChannel(?ChannelInterface $channel): void
    {
        $this->channel = $channel;
    }
}
