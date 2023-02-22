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

namespace Asdoria\SyliusConfiguratorPlugin\Form\Type\Calculator;

use Sylius\Bundle\AttributeBundle\Form\Type\AttributeType\Configuration\SelectAttributeChoicesCollectionType;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductAttributeChoiceBasedFlatRateConfigurationType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entry_type' => ChannelBasedFlatRateConfigurationType::class,
        ]);
    }

    public function getParent(): string
    {
        return ProductAttributeChoicesCollectionType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'asdoria_configurator_plugin_product_attribute_choice_based_configurator_calculator_flat_rate';
    }
}
