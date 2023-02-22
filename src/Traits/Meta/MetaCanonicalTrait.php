<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits\Meta;

/**
 * Trait MetaCanonicalTrait
 * @package Asdoria\SyliusConfiguratorPlugin\Traits\Meta
 */
trait MetaCanonicalTrait
{
    /** @var string|null */
    protected ?string $metaCanonical;

    /**
     * @return string|null
     */
    public function getMetaCanonical(): ?string
    {
        return $this->metaCanonical;
    }

    /**
     * @param string|null $metaCanonical
     */
    public function setMetaCanonical(?string $metaCanonical): void
    {
        $this->metaCanonical = $metaCanonical;
    }
}
