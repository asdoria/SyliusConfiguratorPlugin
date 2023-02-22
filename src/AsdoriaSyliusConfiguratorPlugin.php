<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin;

use Asdoria\SyliusConfiguratorPlugin\DependencyInjection\RegisterCalculatorsPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class AsdoriaSyliusConfiguratorPlugin
 * @package Asdoria\SyliusConfiguratorPlugin
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
final class AsdoriaSyliusConfiguratorPlugin extends Bundle
{
    use SyliusPluginTrait;

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterCalculatorsPass());
    }
}
