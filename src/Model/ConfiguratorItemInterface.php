<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Model;

use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorStepAwareInterface;
use Sylius\Component\Core\Model\ImagesAwareInterface as BaseImagesAwareInterface;
use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

/**
 * Class ConfiguratorItemInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Model
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface ConfiguratorItemInterface extends
    ResourceInterface,
    TimestampableInterface,
    ConfiguratorAwareInterface,
    ToggleableInterface,
    CodeAwareInterface,
    BaseImagesAwareInterface,
    ConfiguratorStepAwareInterface,
    TranslatableInterface
{

}
