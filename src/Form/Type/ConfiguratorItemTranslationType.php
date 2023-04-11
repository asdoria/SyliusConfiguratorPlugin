<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ConfiguratorStepTranslationType
 * @package Asdoria\SyliusConfiguratorPlugin\Form\Type
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorItemTranslationType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'asdoria.form.configurator_item.label',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'asdoria.form.configurator_item.description',
                'required' => false,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'asdoria_configurator_item_translation';
    }
}
