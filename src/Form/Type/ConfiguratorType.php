<?php

declare(strict_types=1);


namespace Asdoria\SyliusConfiguratorPlugin\Form\Type;


use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorStep;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\Calculator\Trait\CalculatorTypeTrait;
use Asdoria\SyliusConfiguratorPlugin\Form\EventSubscriber\CalculatorTypeFormSubscriber;
use Asdoria\SyliusConfiguratorPlugin\Traits\CalculatorRegistryTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\FormBuilderTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\FormTypeRegistryTrait;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAutocompleteChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ConfiguratorType
 * @package Asdoria\SyliusConfiguratorPlugin\Form\Type
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorType extends AbstractResourceType
{
    use CalculatorTypeTrait;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildCalculatorForm($builder, $options);
        $builder
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => ConfiguratorTranslationType::class,
                'label'      => 'asdoria.form.configurator_translation.name',
            ])
            ->add('channels', ChannelChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'label'    => 'sylius.form.product.channels',
            ])
            ->add('images', CollectionType::class, [
                'entry_type'   => ConfiguratorImageType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'        => 'asdoria.form.configurator.images',
            ])
            ->add('products', ProductAutocompleteChoiceType::class, [
                'label' => 'asdoria.form.configurator.product',
                'multiple' => true,
                'required' => false,
                'choice_name' => 'name',
                'choice_value' => 'code',
                'resource' => 'sylius.product',
//                'constraints' => [
//                    new NotBlank(['groups' => ['sylius', 'asdoria_configurator_plugin'], 'message' => 'sylius.catalog_promotion_scope.for_products.not_empty']),
//                ],
            ])
            ->add('configuratorStep', EntityType::class, [
                'label'    => 'asdoria.form.configurator.configurator_step',
                'class'    => ConfiguratorStep::class,
                'required' => true,
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
                'label'    => 'asdoria.form.configurator.enabled',
            ])
            ->add('position', NumberType::class, [
                'label' => 'sylius.ui.position',
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'calculator_choice_type' => CalculatorChoiceType::class,
        ]);
    }
    /**
     * @return string|null
     */
    public function getBlockPrefix(): ?string
    {
        return 'asdoria_configurator';
    }
}
