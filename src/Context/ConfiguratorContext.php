<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Context;

use Asdoria\SyliusConfiguratorPlugin\Context\Model\ConfiguratorContextInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Asdoria\SyliusConfiguratorPlugin\Repository\Model\ConfiguratorRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ConfiguratorContext
 *
 * @author Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorContext implements ConfiguratorContextInterface
{
    /**
     * @param ConfiguratorRepositoryInterface $configuratorRepository
     * @param RequestStack                    $requestStack
     * @param ChannelContextInterface         $channelContext
     */
    public function __construct(
        protected ConfiguratorRepositoryInterface $configuratorRepository,
        protected RequestStack $requestStack,
        protected ChannelContextInterface $channelContext
    ){
    }

    /**
     * @return ConfiguratorInterface|null
     */
    public function getConfigurator(): ?ConfiguratorInterface {
        $slug = $this->requestStack->getMainRequest()->attributes->get('slug', null);
        $locale = $this->requestStack->getMainRequest()->attributes->get('_locale', null);
        $channel = $this->channelContext->getChannel();
        return $this->configuratorRepository->findOneBySlug($slug, $locale, $channel);
    }
}

