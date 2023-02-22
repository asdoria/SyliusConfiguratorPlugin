<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits\Meta;

/**
 * Trait MetaImageTrait
 * @package Asdoria\SyliusConfiguratorPlugin\Traits\Meta
 */
trait MetaImageTrait
{
    /** @var string|null */
    protected ?string $metaImage;

    /**
     * @return string|null
     */
    public function getMetaImage(): ?string
    {
        return $this->metaImage;
    }

    /**
     * @param string|null $metaImage
     */
    public function setMetaImage(?string $metaImage): void
    {
        $this->metaImage = $metaImage;
    }
}
