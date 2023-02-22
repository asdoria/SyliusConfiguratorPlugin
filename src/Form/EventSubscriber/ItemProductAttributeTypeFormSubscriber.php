<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Form\EventSubscriber;

use Asdoria\SyliusConfiguratorPlugin\Calculator\Model\CalculatorInterface;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorItemAdditionalProductTranslation;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorItemProductAttributeTranslation;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\CalculatorChoiceType;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\ConfigurationCheckboxType;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\ConfiguratorItemProductAttributeTranslationType;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\MultipleCheckboxType;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ProductAttributeAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ProductAwareInterface;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAttributeChoiceType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAutocompleteChoiceType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\Component\Attribute\AttributeType\SelectAttributeType;
use Sylius\Component\Product\Model\ProductAttributeInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ConfiguratorItemType
 * @package Asdoria\SyliusConfiguratorPlugin\Form\Type
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ItemProductAttributeTypeFormSubscriber extends CalculatorTypeFormSubscriber implements EventSubscriberInterface
{

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event): void
    {
        $resource = $event->getData();
        if (!$resource instanceof ProductAttributeAwareInterface) {
            return;
        }

        parent::preSetData($event);

        $event->getForm()
            ->add('productAttribute', ProductAttributeChoiceType::class, [
                'label' => 'asdoria.form.configurator_item_product_attribute.product_attribute'
            ])
            ->add('required', ConfigurationCheckboxType::class, [
                'label' => 'asdoria.form.configurator_item_product_attribute.is_required',
                'configuration' => $resource->getConfiguration(),
                'field' => 'required'
            ])
        ;

        $this->addMultipleField($resource, $event);
    }

    /**
     * @param ProductAttributeAwareInterface $resource
     *
     * @return void
     */
    protected function addMultipleField(ProductAttributeAwareInterface $resource, FormEvent $event) {
        if (!$resource->getProductAttribute() instanceof ProductAttributeInterface) {
            return;
        }

        if (!isset($resource->getProductAttribute()->getConfiguration()['multiple'])) {
            return;
        }

        $event->getForm()->add('multiple', ConfigurationCheckboxType::class, [
            'label' => 'asdoria.form.configurator_item_product_attribute.select.multiple',
            'configuration' => $resource->getConfiguration(),
            'field' => 'multiple'
        ]);
    }

    /**
     * @psalm-suppress MissingPropertyType
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['rules_help'] = $options['rules_help'] ?? '';

        $view->vars['prototypes'] = [];
        foreach ($form->getConfig()->getAttribute('prototypes') as $group => $prototypes) {
            foreach ($prototypes as $type => $prototype) {
                $view->vars['prototypes'][$group . '_' . $type] = $prototype->createView($view);
            }
        }
    }

}
