<?php


declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Model;

use Asdoria\SyliusConfiguratorPlugin\Model\Aware\MetaCanonicalAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\MetaDescriptionAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\MetaKeywordsAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\MetaRobotsAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\MetaTitleAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\SlugAwareInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * Interface ConfiguratorTranslationInterface
 * @package Asdoria\SyliusConfiguratorPlugin\Model
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface ConfiguratorTranslationInterface extends 
    SlugAwareInterface, 
    ResourceInterface, 
    TranslationInterface,
    MetaTitleAwareInterface,
    MetaKeywordsAwareInterface,
    MetaDescriptionAwareInterface,
    MetaRobotsAwareInterface,
    MetaCanonicalAwareInterface
{
    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getDescription(): ?string;

    public function setDescription(?string $description): void;
}
