<?php

declare(strict_types=1);


namespace Asdoria\SyliusConfiguratorPlugin\Traits;


/**
 * Class NamingTrait
 * @package Asdoria\SyliusConfiguratorPlugin\Traits
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
trait NamingTrait
{
    /** @var string|null */
    protected ?string $name = null;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function __toString() {
        return $this->getName();
    }
}
