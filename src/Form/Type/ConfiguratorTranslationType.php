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

namespace Asdoria\SyliusConfiguratorPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ConfiguratorTranslationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'asdoria.form.configurator.name',
            ])
            ->add('slug', TextType::class, [
                'label' => 'asdoria.form.configurator.slug',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'asdoria.form.configurator.description',
            ])
            ->add('metaTitle', TextType::class, [
                'label'    => 'asdoria.form.configurator.meta_title',
                'required' => false,
            ])
            ->add('metaDescription', TextareaType::class, [
                'label'    => 'asdoria.form.configurator.meta_description',
                'required' => false,
            ])
            ->add('metaKeywords', TextType::class, [
                'label'    => 'asdoria.form.configurator.meta_keywords',
                'required' => false,
            ])
            ->add('metaRobots', TextType::class, [
                'label'    => 'asdoria.form.configurator.meta_robots',
                'required' => false,
            ])
            ->add('metaCanonical', TextareaType::class, [
                'label'    => 'asdoria.form.configurator.meta_canonical',
                'required' => false,
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'asdoria_configurator_translation';
    }
}
