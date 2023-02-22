<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Entity;

use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorStepImageInterface;
use Asdoria\SyliusConfiguratorPlugin\Traits\ResourceTrait;
use Sylius\Component\Core\Model\Image;

/**
 * Class ConfiguratorStepImage
 * @package Asdoria\SyliusConfiguratorPlugin\Entity
 */
class ConfiguratorStepImage extends Image implements ConfiguratorStepImageInterface
{
    use ResourceTrait;
}
