<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Repository;

use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorStepInterface;
use Asdoria\SyliusConfiguratorPlugin\Repository\Model\ConfiguratorStepRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use SyliusLabs\AssociationHydrator\AssociationHydrator;

/**
 * Class ConfiguratorStepRepository
 * @package Asdoria\SyliusConfiguratorPlugin\Repository
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorStepRepository extends EntityRepository  implements ConfiguratorStepRepositoryInterface
{
    /** @var AssociationHydrator */
    private AssociationHydrator $associationHydrator;

    /**
     * {@inheritdoc}
     */
    public function __construct(EntityManager $entityManager, ClassMetadata $class)
    {
        parent::__construct($entityManager, $class);

        $this->associationHydrator = new AssociationHydrator($entityManager, $class);
    }


    /**
     * @return QueryBuilder
     */
    public function createListQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('o');
    }


    /**
     * @param string $phrase
     * @param string $locale
     *
     * @return array
     */
    public function findByPhrase(string $phrase, string $locale): array
    {
        $expr = $this->getEntityManager()->getExpressionBuilder();

        return $this->createQueryBuilder('o')
            ->innerJoin('o.translations', 'translation', 'WITH', 'translation.locale = :locale')
            ->andWhere($expr->orX(
                'translation.name LIKE :phrase',
                'o.code LIKE :phrase'
            ))
            ->setParameter('phrase', '%' . $phrase . '%')
            ->setParameter('locale', $locale)
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();
    }
}
