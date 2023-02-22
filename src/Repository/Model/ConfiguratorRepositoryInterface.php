<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Repository\Model;

use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * Interface ConfiguratorRepositoryInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Repository\Model
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface ConfiguratorRepositoryInterface extends RepositoryInterface
{
    /**
     * @param string           $slug
     * @param string           $locale
     * @param ChannelInterface $channel
     *
     * @return ConfiguratorInterface|null
     */
    public function findOneBySlug(string $slug, string $locale, ChannelInterface $channel): ?ConfiguratorInterface;

    /**
     * @param string      $phrase
     * @param string|null $locale
     * @param null        $limit
     *
     * @return array
     */
    public function findByNamePart(string $phrase, ?string $locale = null, $limit = null): array;

    /**
     * @param ChannelInterface $channel
     * @param string           $locale
     * @param int              $count
     *
     * @return array
     */
    public function findLatestByChannelAndHighlighted(ChannelInterface $channel, string $locale, int $count): array;
}
