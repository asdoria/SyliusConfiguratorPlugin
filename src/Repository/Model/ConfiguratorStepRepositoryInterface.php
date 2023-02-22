<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Repository\Model;

use Doctrine\ORM\QueryBuilder;

/**
 * Class ConfiguratorStepRepositoryInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Repository\Model
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface ConfiguratorStepRepositoryInterface
{

    /**
     * @return QueryBuilder
     */
    public function createListQueryBuilder(): QueryBuilder;


    /**
     * @param string $phrase
     * @param string $locale
     *
     * @return array
     */
    public function findByPhrase(string $phrase, string $locale): array;
}
