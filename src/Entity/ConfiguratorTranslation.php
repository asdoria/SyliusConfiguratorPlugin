<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Entity;

use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorTranslationInterface;
use Asdoria\SyliusConfiguratorPlugin\Traits\Meta\MetaCanonicalTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\Meta\MetaDescriptionTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\Meta\MetaKeywordsTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\Meta\MetaRobotsTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\Meta\MetaTitleTrait;
use Sylius\Component\Resource\Model\AbstractTranslation;

/**
 * Class ConfiguratorTranslation
 * @package Asdoria\SyliusConfiguratorPlugin\Entity
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorTranslation extends AbstractTranslation implements ConfiguratorTranslationInterface
{
    use MetaDescriptionTrait;
    use MetaKeywordsTrait;
    use MetaRobotsTrait;
    use MetaTitleTrait;
    use MetaCanonicalTrait;

    /** @var mixed */
    protected $id;

    /** @var string|null */
    protected ?string $name = null;

    /** @var string|null */
    protected ?string $slug= null;

    /** @var string|null */
    protected ?string $description= null;

    public function __toString(): string
    {
        return (string) $this->getName();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
