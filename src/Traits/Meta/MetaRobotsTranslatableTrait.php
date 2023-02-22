<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits\Meta;

use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * Trait MetaRobotsTrait
 * @package Asdoria\SyliusConfiguratorPlugin\Traits\Meta
 */
trait MetaRobotsTranslatableTrait
{
    /**
     * @return string|null
     */
    public function getMetaRobots(): ?string
    {
        return $this->getTranslation()->getMetaRobots();
    }

    /**
     * @param string|null $metaRobots
     */
    public function setMetaRobots(?string $metaRobots): void
    {
        $this->getTranslation()->setMetaRobots($metaRobots);
    }

    /**
     * @param string|null $locale
     *
     * @return TranslationInterface
     */
    abstract public function getTranslation(?string $locale = null): TranslationInterface;
}
