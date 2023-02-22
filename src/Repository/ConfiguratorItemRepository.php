<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Repository;


use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemInterface;
use Asdoria\SyliusConfiguratorPlugin\Repository\Model\ConfiguratorItemRepositoryInterface;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * Class ConfiguratorItemRepository
 * @package Asdoria\SyliusConfiguratorPlugin\Repository
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorItemRepository extends EntityRepository  implements ConfiguratorItemRepositoryInterface
{


    public function createQueryBuilderByConfiguratorId(string $configuratorId): QueryBuilder
    {
        $qb = $this->createQueryBuilder('o');
        $qb->andWhere('o.configurator = :configurator')
            ->setParameter('configurator', $configuratorId);
        return $qb;
    }


    /**
     * @param string $configuratorId
     * @param string $id
     *
     * @return ConfiguratorItemInterface|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByConfiguratorId(string $configuratorId, string $id): ?ConfiguratorItemInterface
    {
        return $this->createQueryBuilderByConfiguratorId($configuratorId)
            ->andWhere('o.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
