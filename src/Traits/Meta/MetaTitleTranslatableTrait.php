<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits\Meta;

use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * Trait MetaTitleTrait
 * @package Asdoria\SyliusConfiguratorPlugin\Traits\Meta
 */
trait MetaTitleTranslatableTrait
{
    /**
     * @return string|null
     */
    public function getMetaTitle(): ?string
    {
        return $this->getTranslation()->getMetaTitle();
    }

    /**
     * @param string|null $metaTitle
     */
    public function setMetaTitle(?string $metaTitle): void
    {
        $this->getTranslation()->setMetaTitle($metaTitle);
    }

    /**
     * @param string|null $locale
     *
     * @return TranslationInterface
     */
    abstract public function getTranslation(?string $locale = null): TranslationInterface;
}
