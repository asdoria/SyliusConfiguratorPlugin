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

namespace Asdoria\SyliusConfiguratorPlugin\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterCalculatorsPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function process(ContainerBuilder $container): void
    {
        $this->registryCalculators('configurator_calculator', $container);
        $this->registryCalculators('configurator_item_calculator', $container);
    }

    /**
     * @param string $calculatorName
     *
     * @return void
     *
     */
    protected function registryCalculators(string $calculatorName, ContainerBuilder $container): void {
        if (!$container->hasDefinition(sprintf('asdoria_configurator_plugin.registry.%s', $calculatorName)) ||
            !$container->hasDefinition(sprintf('asdoria_configurator_plugin.form_registry.%s', $calculatorName))) {
            return;
        }

        $registry = $container->getDefinition(sprintf('asdoria_configurator_plugin.registry.%s', $calculatorName));
        $formTypeRegistry = $container->getDefinition(sprintf('asdoria_configurator_plugin.form_registry.%s', $calculatorName));
        $calculators = [];

        foreach ($container->findTaggedServiceIds(sprintf('asdoria_configurator_plugin.%s', $calculatorName)) as $id => $attributes) {
            foreach ($attributes as $attribute) {
                if (!isset($attribute['calculator'], $attribute['label'])) {
                    throw new \InvalidArgumentException(sprintf('Tagged %s needs to have `calculator` and `label` attributes.', $calculatorName));
                }

                $name = $attribute['calculator'];
                $calculators[$name] = $attribute['label'];

                $registry->addMethodCall('register', [$name, new Reference($id)]);

                if (isset($attribute['form_type'])) {
                    $formTypeRegistry->addMethodCall('add', [$name, 'default', $attribute['form_type']]);
                }
            }
        }

        $container->setParameter(sprintf('asdoria_configurator_plugin.%ss', $calculatorName), $calculators);
    }
}
