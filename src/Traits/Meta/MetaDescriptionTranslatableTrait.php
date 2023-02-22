<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits\Meta;

use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * Trait MetaDescriptionTranslatableTrait
 * @package Asdoria\SyliusConfiguratorPlugin\Traits\Meta
 */
trait MetaDescriptionTranslatableTrait
{
    /**
     * @return string|null
     */
    public function getMetaDescription(): ?string
    {
        return $this->getTranslation()->getMetaDescription();
    }

    /**
     * @param string|null $metaDescription
     */
    public function setMetaDescription(?string $metaDescription): void
    {
        $this->getTranslation()->setMetaDescription($metaDescription);
    }

    /**
     * @param string|null $locale
     *
     * @return TranslationInterface
     */
    abstract public function getTranslation(?string $locale = null): TranslationInterface;
}
