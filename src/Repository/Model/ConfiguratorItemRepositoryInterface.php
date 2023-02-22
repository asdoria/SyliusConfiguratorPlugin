<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Repository\Model;

use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Interface ConfiguratorItemRepositoryInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Repository\Model
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface ConfiguratorItemRepositoryInterface
{
    public function createQueryBuilderByConfiguratorId(string $configuratorId): QueryBuilder;

    /**
     * @param string $configuratorId
     * @param string $id
     *
     * @return ConfiguratorItemInterface|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByConfiguratorId(string $configuratorId, string $id): ?ConfiguratorItemInterface;
}
