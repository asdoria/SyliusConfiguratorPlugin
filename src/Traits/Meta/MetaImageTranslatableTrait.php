<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits\Meta;

use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * Trait MetaImageTrait
 * @package Asdoria\SyliusConfiguratorPlugin\Traits\Meta
 */
trait MetaImageTranslatableTrait
{
    /**
     * @return string|null
     */
    public function getMetaImage(): ?string
    {
        return $this->getTranslation()->getMetaImage();
    }

    /**
     * @param string|null $metaImage
     */
    public function setMetaImage(?string $metaImage): void
    {
        $this->getTranslation()->setMetaImage($metaImage);
    }

    /**
     * @param string|null $locale
     *
     * @return TranslationInterface
     */
    abstract public function getTranslation(?string $locale = null): TranslationInterface;
}
