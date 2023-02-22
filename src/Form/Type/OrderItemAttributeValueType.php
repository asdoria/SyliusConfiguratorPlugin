<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Form\Type;

use Asdoria\SyliusConfiguratorPlugin\Calculator\ProductAttributeChoiceFlatRateCalculator;
use Asdoria\SyliusConfiguratorPlugin\Calculator\ProductAttributeChoicePercentageCalculator;
use Asdoria\SyliusConfiguratorPlugin\Controller\Shop\ConfiguratorAddToCartCommandInterface;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorItemProductAttribute;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Asdoria\SyliusConfiguratorPlugin\Traits\ChannelContextTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\CurrencyContextTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\MoneyFormatterTrait;
use Sylius\Bundle\AttributeBundle\Form\Type\AttributeValueType;
use Sylius\Bundle\LocaleBundle\Form\Type\LocaleChoiceType;
use Sylius\Bundle\ResourceBundle\Form\DataTransformer\ResourceToIdentifierTransformer;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\ChoiceList\Factory\Cache\ChoiceFilter;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\ReversedTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * Class OrderItemAttributeValueType
 * @package Asdoria\SyliusConfiguratorPlugin\Form\Type\CartItem
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class OrderItemAttributeValueType extends OrderItemAttributeValueChoiceType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $attributeValue = $event->getData();
                if (!$attributeValue instanceof AttributeValueInterface) {
                    return;
                }
                $event->getForm()
                    ->add('localeCode', HiddenType::class, [
                        'empty_data' => $attributeValue->getLocaleCode()
                    ])
                    ->add('attribute', AttributeHiddenType::class, [
                        'empty_data' => function (FormInterface $form) use ($attributeValue) {
                            return $attributeValue->getAttribute()->getCode();
                        },
                    ]);

                $attribute = $attributeValue->getAttribute();
                if (null === $attribute) {
                    return;
                }

                $localeCode = $attributeValue->getLocaleCode();

                $this->addValueField($event->getForm(), $attribute, $localeCode);

            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $attributeValue = $event->getData();

                if (!isset($attributeValue['attribute'])) {
                    return;
                }

                $attribute = $this->attributeRepository->findOneBy(['code' => $attributeValue['attribute']]);

                if (!$attribute instanceof AttributeInterface) {
                    return;
                }

                $this->addValueField($event->getForm(), $attribute);
            });
    }

    protected function addValueField(
        FormInterface      $form,
        AttributeInterface $attribute,
        ?string            $localeCode = null,
    ): void
    {
        /** @var ConfiguratorInterface $configurator */
        /** @var ChannelInterface $channel */
        $configurator  = $form->getConfig()->getOption('configurator');
        $step          = $form->getData()?->getConfiguratorStep();
        $item          = $configurator->getItemProductAttributeByStepAndAttribute($step, $attribute);
        $intersect     = array_intersect_key($item?->getConfiguration() ?? [], $attribute->getConfiguration());
        $configuration = array_merge(
            $attribute->getConfiguration(),
            $intersect
        );

        $options = [
            'auto_initialize' => false,
            'configuration'   => $configuration,
            'label'           => $item?->getName(),
            'locale_code'     => $localeCode,
            'required'        => $item->getConfiguration()['required'] ?? false,
        ];

        $this->addChoiceOptions($options, $form, $attribute, $item, $localeCode);

        $form->add('value', $this->formTypeRegistry->get($attribute->getType(), 'default'), $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired('configurator')
            ->setDefault('entry_name', 'asdoria_configurator_add_to_cart_cartItem_attributes_entry')
            ->setAllowedTypes('configurator', [ConfiguratorInterface::class]);
    }

    public function getBlockPrefix(): string
    {
        return 'asdoria_configurator_order_item_attribute_value';
    }
}
