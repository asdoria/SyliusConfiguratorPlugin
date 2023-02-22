<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Repository;

use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Asdoria\SyliusConfiguratorPlugin\Repository\Model\ConfiguratorRepositoryInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\ChannelInterface;

/**
 * Class ConfiguratorRepository
 * @package Asdoria\SyliusConfiguratorPlugin\Doctrine\ORM
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorRepository extends EntityRepository implements ConfiguratorRepositoryInterface
{

    /**
     * @param string           $slug
     * @param string           $locale
     * @param ChannelInterface $channel
     *
     * @return ConfiguratorInterface|null
     * @throws NonUniqueResultException
     */
    public function findOneBySlug(string $slug, string $locale, ChannelInterface $channel): ?ConfiguratorInterface
    {
        return $this->createTranslationBasedQueryBuilder($locale)
            ->innerJoin('o.channels', 'channel')
            ->andWhere('o.enabled = true')
            ->andWhere('translation.slug = :slug')
            ->andWhere('translation.locale = :locale')
            ->andWhere('channel = :channel')
            ->setParameter('slug', $slug)
            ->setParameter('locale', $locale)
            ->setParameter('channel', $channel)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param string      $phrase
     * @param string|null $locale
     * @param null        $limit
     *
     * @return array
     */
    public function findByNamePart(string $phrase, ?string $locale = null, $limit = null): array
    {
        $maxResults = $limit ? intval($limit) : null;

        return $this->createTranslationBasedQueryBuilder($locale)
            ->andWhere('translation.name LIKE :name')
            ->setParameter('name', '%' . $phrase . '%')
            ->setMaxResults($maxResults > 0 ? $maxResults : null)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param string|null $locale
     *
     * @return QueryBuilder
     */
    protected function createTranslationBasedQueryBuilder(?string $locale): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->leftJoin('o.translations', 'translation')
        ;

        if (null !== $locale) {
            $queryBuilder
                ->andWhere('translation.locale = :locale')
                ->setParameter('locale', $locale)
            ;
        }

        return $queryBuilder;
    }


    /**
     * @param ChannelInterface $channel
     * @param string           $locale
     * @param int              $count
     *
     * @return array
     */
    public function findLatestByChannelAndHighlighted(ChannelInterface $channel, string $locale, int $count): array
    {
        return $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->innerJoin('o.translations', 'translation', 'WITH', 'translation.locale = :locale')
            ->andWhere(':channel MEMBER OF o.channels')
            ->andWhere('o.enabled = :enabled')
            ->andWhere('o.archivedAt is NULL')
            ->andWhere('o.highlighted = true')
            ->addOrderBy('o.createdAt', 'DESC')
            ->setParameter('channel', $channel)
            ->setParameter('locale', $locale)
            ->setParameter('enabled', true)
            ->setMaxResults($count)
            ->getQuery()
            ->getResult()
            ;
    }

}
