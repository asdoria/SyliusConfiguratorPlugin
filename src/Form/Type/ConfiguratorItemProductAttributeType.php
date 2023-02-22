<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Form\Type;

use Asdoria\SyliusConfiguratorPlugin\Form\Type\Calculator\Trait\CalculatorTypeTrait;
use Asdoria\SyliusConfiguratorPlugin\Form\EventSubscriber\CalculatorTypeFormSubscriber;
use Asdoria\SyliusConfiguratorPlugin\Traits\CalculatorRegistryTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\FormBuilderTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\FormTypeRegistryTrait;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAttributeChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ConfiguratorItemType
 * @package Asdoria\SyliusConfiguratorPlugin\Form\Type
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorItemProductAttributeType extends ConfiguratorItemType
{

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'calculator_choice_type' => CalculatorItemChoiceType::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'asdoria_configurator_item_product_attribute';
    }
}
