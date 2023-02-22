<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits\Meta;

/**
 * Trait MetaRobotsTrait
 * @package Asdoria\SyliusConfiguratorPlugin\Traits\Meta
 */
trait MetaRobotsTrait
{
    /** @var string|null */
    protected ?string $metaRobots;

    /**
     * @return string|null
     */
    public function getMetaRobots(): ?string
    {
        return $this->metaRobots;
    }

    /**
     * @param string|null $metaRobots
     */
    public function setMetaRobots(?string $metaRobots): void
    {
        $this->metaRobots = $metaRobots;
    }
}
