<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits\Meta;

use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * Trait MetaCanonicalTranslatableTrait
 * @package Asdoria\SyliusConfiguratorPlugin\Traits\Meta
 */
trait MetaCanonicalTranslatableTrait
{
    /**
     * @return string|null
     */
    public function getMetaCanonical(): ?string
    {
        return $this->getTranslation()->getMetaCanonical();
    }

    /**
     * @param string|null $metaCanonical
     */
    public function setMetaCanonical(?string $metaCanonical): void
    {
        $this->getTranslation()->setMetaCanonical($metaCanonical);
    }

    /**
     * @param string|null $locale
     *
     * @return TranslationInterface
     */
    abstract public function getTranslation(?string $locale = null): TranslationInterface;
}
