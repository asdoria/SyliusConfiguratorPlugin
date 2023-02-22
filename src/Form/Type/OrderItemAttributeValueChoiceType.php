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
use Asdoria\SyliusConfiguratorPlugin\Traits\RouterTrait;
use Sylius\Bundle\AttributeBundle\Form\Type\AttributeValueType;
use Sylius\Bundle\LocaleBundle\Form\Type\LocaleChoiceType;
use Sylius\Bundle\ResourceBundle\Form\DataTransformer\ResourceToIdentifierTransformer;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductInterface;
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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * Class OrderItemAttributeValueType
 * @package Asdoria\SyliusConfiguratorPlugin\Form\Type\CartItem
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
abstract class OrderItemAttributeValueChoiceType extends AttributeValueType
{
    use ChannelContextTrait;
    use CurrencyContextTrait;
    use MoneyFormatterTrait;
    use RouterTrait;

    protected function addChoiceOptions(
        array              &$options,
        FormInterface      $form,
        AttributeInterface $attribute,
        ?ConfiguratorItemProductAttribute $item,
        ?string            $localeCode = null,
    ): void
    {
        $intersect     = array_intersect_key($item?->getConfiguration() ?? [], $attribute->getConfiguration());
        $channel       = $this->getChannelContext()->getChannel();
        $configuration = array_merge(
            $attribute->getConfiguration(),
            $intersect
        );

        if (!isset($configuration['choices'])) {
            return;
        }

        $currencyCode = $this->currencyContext->getCurrencyCode();
        /** @var ChannelInterface $channel */
        $channel      = $this->getChannelContext()->getChannel();
        $options['choice_filter'] = $this->choiceFilter($configuration, $form, $attribute, $localeCode);
        $options['choice_label']  = $this->choiceLabel($item, $channel, $localeCode);
        $options['choice_attr']   = $this->choiceAttr($form, $item);
        $options['attr']          = array_merge_recursive($options['attr']?? [], ['class' => 'js-image-switcher', 'data-step-code' => $item->getConfiguratorStep()?->getCode()]);
    }

    /**
     * @return CallbackChoiceLoader
     */
    protected function choiceAttr(FormInterface $form, ConfiguratorItemProductAttribute $item): \Closure
    {
        return function ($key) use ($item, $form) {
            $path    = null;
            $product = $this->guessProduct($form);
            $image   = $product?->getImagesByType($key)?->first();
            if ($image instanceof ImageInterface) {
                $path = $this->router->generate(
                    'liip_imagine_filter',
                    ['path' => $image->getPath(), 'filter' => 'sylius_shop_product_large_thumbnail'],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );
            }

            $attr    = [
                'data-image-path'     => $path,
                'data-attribute-code' => $item->getProductAttribute()?->getCode()
            ];

            return $attr;
        };
    }
    /**
     * @param ConfiguratorItemProductAttribute $item
     * @param ChannelInterface                 $channel
     * @param string|null                      $localeCode
     *
     * @return \Closure
     */
    protected function choiceLabel(ConfiguratorItemProductAttribute $item, ChannelInterface $channel, ?string $localeCode = null): \Closure {
        return function ($key, $label) use ($item, $channel, $localeCode) {
            $row = $item->getConfiguration()[$key] ?? [];
            if(empty($row)) return $label;
            if (isset($row['percentage']) && $item->getCalculator() !== ProductAttributeChoiceFlatRateCalculator::_TYPE) {
                return sprintf('%s (+%s)', $label, ($row['percentage'] ?? 0) * 100 . '%');
            }
            $amount  = $row[$channel->getCode()]['amount'] ?? 0;
            if (empty($amount)) return $label;

            $currencyCode = $this->currencyContext->getCurrencyCode();
            return sprintf('%s (+%s)', $label, $this->moneyFormatter->format($amount, $currencyCode, $localeCode));
        };
    }

    /**
     * @param array              $configuration
     * @param FormInterface      $form
     * @param AttributeInterface $attribute
     * @param string|null        $localeCode
     *
     * @return ChoiceFilter
     */
    protected function choiceFilter(
        array $configuration,
        FormInterface $form,
        AttributeInterface $attribute,
        ?string $localeCode = null
    ): ChoiceFilter {
        return ChoiceList::filter(
            $this,
            function ($choice) use ($form, $localeCode, $attribute) {

                $product   = $this->guessProduct($form);

                if (null === $product) return true;

                $attrValue = $product->getAttributeByCodeAndLocale($attribute->getCode(), $localeCode);

                $target = $attrValue?->getValue() ?? [];

                if (empty($target)) return true;
                return in_array($choice, $attrValue?->getValue() ?? []);
            },
            $configuration['choices'] ?? []
        );
    }

    public function guessProduct(FormInterface $form): ?ProductInterface {
        $configuratorAddToCartCommand = $form->getRoot()->getData();
        if (!$configuratorAddToCartCommand instanceof ConfiguratorAddToCartCommandInterface ||
            !$configuratorAddToCartCommand->getCartItem() instanceof OrderItemInterface ||
            !$configuratorAddToCartCommand->getCartItem()->getVariant() instanceof ProductVariantInterface
        ) {
            return null;
        }

        return $configuratorAddToCartCommand->getCartItem()->getProduct();
    }
}
