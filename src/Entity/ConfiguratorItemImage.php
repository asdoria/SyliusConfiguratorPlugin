<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Entity;

use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemImageInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorStepImageInterface;
use Sylius\Component\Core\Model\Image;

/**
 * Class ConfiguratorItemImage
 * @package Asdoria\SyliusConfiguratorPlugin\Entity
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorItemImage extends Image implements ConfiguratorItemImageInterface
{
}
