<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ConfigurationCheckboxType
 * @package Asdoria\SyliusConfiguratorPlugin\Form\Type
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfigurationCheckboxType  extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        ['configuration' => $configuration,'field' => $field] = $options;
        if (is_array($configuration) &&
            isset($configuration[$field]) &&
            !$configuration[$field]) {
            $builder->addModelTransformer(new CallbackTransformer(
            /**
             * @param mixed $array
             *
             * @return mixed
             */
                function ($array) {
                    if (is_array($array) && count($array) > 0) {
                        return $array[0];
                    }

                    return null;
                },
                /** @param mixed $string */
                function ($string): bool {
                    return $string;
                },
            ));
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired('configuration')
            ->setRequired('field')
        ;
    }

    public function getParent(): string
    {
        return CheckboxType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'asdoria_configurator_configuration_checkbox';
    }
}
