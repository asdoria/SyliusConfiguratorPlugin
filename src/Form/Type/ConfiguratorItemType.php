<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Form\Type;

use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorStep;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\Calculator\Trait\CalculatorTypeTrait;
use Asdoria\SyliusConfiguratorPlugin\Form\EventSubscriber\ItemAdditionalProductTypeFormSubscriber;
use Asdoria\SyliusConfiguratorPlugin\Form\EventSubscriber\ItemProductAttributeTypeFormSubscriber;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorStepInterface;
use Asdoria\SyliusConfiguratorPlugin\Traits\CalculatorRegistryTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\FormBuilderTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\FormTypeRegistryTrait;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ConfiguratorItemType
 * @package Asdoria\SyliusConfiguratorPlugin\Form\Type
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorItemType extends AbstractResourceType
{
    use CalculatorTypeTrait;
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->buildCalculatorForm($builder, $options);
        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->addEventSubscriber(new ItemAdditionalProductTypeFormSubscriber())
            ->add('position', IntegerType::class, [
                'required' => false,
                'label' => 'asdoria.form.configurator_item.position',
                'invalid_message' => 'asdoria.configurator.invalid',
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => ConfiguratorItemTranslationType::class,
                'label' => 'asdoria.form.configurator_items.translations',
            ])
            ->add('configuratorStep', ConfiguratorStepAutocompleteChoiceType::class, [
                'label'    => 'asdoria.form.configurator_item.configurator_step',
                'required' => true,
            ])
            ->add('images', CollectionType::class, [
                'entry_type'   => ConfiguratorItemImageType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'        => 'asdoria.form.configurator_items.images',
            ])
        ;
        if (!empty($this->formTypeRegistry) || !empty($this->calculatorRegistry)) {
            $builder->addEventSubscriber(new ItemProductAttributeTypeFormSubscriber(
                $this->formTypeRegistry,
                $this->calculatorRegistry
            ));
        }
    }

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
        return 'asdoria_configurator_item';
    }
}
