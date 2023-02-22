<?php

declare(strict_types=1);


namespace Asdoria\SyliusConfiguratorPlugin\Traits;

/**
 * Trait ResourceTrait
 * @package Asdoria\SyliusConfiguratorPlugin\Traits
 */
trait ResourceTrait
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
