<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Context\Model;

use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;


/**
 * Class ConfiguratorContextInterface
 *
 * @author Philippe Vesin <pve.asdoria@gmail.com>
 */
interface ConfiguratorContextInterface
{
    /**
     * @return ConfiguratorInterface|null
     */
    public function getConfigurator(): ?ConfiguratorInterface;
}
