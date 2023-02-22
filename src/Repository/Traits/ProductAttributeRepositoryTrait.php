<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Repository\Traits;


use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Product\Model\ProductAttributeInterface;

/**
 * Trait ProductRepositoryTrait
 * @package Asdoria\Bundle\SearchFilterBundle\Repository\Traits
 */
trait ProductAttributeRepositoryTrait
{

    /**
     * Creates a new QueryBuilder instance that is prepopulated for this entity name.
     *
     * @param string $alias
     * @param string $indexBy The index for the from.
     *
     * @return QueryBuilder
     */
    abstract public function createQueryBuilder($alias, $indexBy = null);

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
     * @param string      $phrase
     * @param string|null $locale
     * @param             $limit
     *
     * @return ProductAttributeInterface[]
     */
    public function findByNamePart(string $phrase, ?string $locale = null, $limit = null): array
    {
        /** @var TaxonInterface[] $results */
        $results = $this->createTranslationBasedQueryBuilder($locale)
            ->andWhere('translation.name LIKE :name')
            ->setParameter('name', '%' . $phrase . '%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;

        foreach ($results as $result) {
            $result->setFallbackLocale(array_key_first($result->getTranslations()->toArray()));
        }

        return $results;
    }
}
