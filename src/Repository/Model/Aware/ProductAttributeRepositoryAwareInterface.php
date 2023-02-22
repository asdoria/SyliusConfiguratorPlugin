<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Repository\Model\Aware;

use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Product\Model\ProductAttributeInterface;

/**
 * Interface ConfiguratorRepositoryAwareInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Repository\Model\Aware
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface ProductAttributeRepositoryAwareInterface
{

    /**
     * @param string      $phrase
     * @param string|null $locale
     * @param             $limit
     *
     * @return ProductAttributeInterface[]
     */
    public function findByNamePart(string $phrase, ?string $locale = null, $limit = null): array;
}
