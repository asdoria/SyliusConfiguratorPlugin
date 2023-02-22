<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Entity;

use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemTranslationInterface;
use Asdoria\SyliusConfiguratorPlugin\Traits\NamingTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\ResourceTrait;
use Sylius\Component\Resource\Model\AbstractTranslation;

/**
 * Class ConfiguratorItemTranslation
 * @package Asdoria\SyliusConfiguratorPlugin\Entity
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorItemTranslation extends AbstractTranslation implements ConfiguratorItemTranslationInterface
{
    use ResourceTrait;
    use NamingTrait;


    /** @var string|null */
    protected ?string $description= null;

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
