<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits;


use Sylius\Component\Channel\Context\ChannelContextInterface;

/**
 * 
 */
trait ChannelContextTrait
{
    /**
     * @var ChannelContextInterface|null
     */
    protected ?ChannelContextInterface $channelContext;

    /**
     * @return ChannelContextInterface|null
     */
    public function getChannelContext(): ?ChannelContextInterface
    {
        return $this->channelContext;
    }

    /**
     * @param ChannelContextInterface|null $channelContext
     */
    public function setChannelContext(?ChannelContextInterface $channelContext): void
    {
        $this->channelContext = $channelContext;
    }
}
