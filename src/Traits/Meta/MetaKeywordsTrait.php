<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits\Meta;

/**
 * Trait MetaKeywordsTrait
 * @package Asdoria\SyliusConfiguratorPlugin\Traits\Meta
 */
trait MetaKeywordsTrait
{
    /** @var string|null */
    protected ?string $metaKeywords;

    /**
     * @return string|null
     */
    public function getMetaKeywords(): ?string
    {
        return $this->metaKeywords;
    }

    /**
     * @param string|null $metaKeywords
     */
    public function setMetaKeywords(?string $metaKeywords): void
    {
        $this->metaKeywords = $metaKeywords;
    }
}
