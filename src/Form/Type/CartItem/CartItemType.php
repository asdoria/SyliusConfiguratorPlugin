<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Form\Type\CartItem;

use Asdoria\SyliusConfiguratorPlugin\Calculator\Model\DelegatingCalculatorInterface;
use Asdoria\SyliusConfiguratorPlugin\Factory\Model\BulkAddToCartCommandFactoryInterface;
use Asdoria\SyliusConfiguratorPlugin\Form\EventSubscriber\OrderItemAttributeValueTypeFormSubscriber;
use Asdoria\SyliusConfiguratorPlugin\Form\EventSubscriber\OrderItemUnitPriceTypeFormSubscriber;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorStepInterface;
use Asdoria\SyliusConfiguratorPlugin\Traits\ConfiguratorCalculatorTrait;
use Sylius\Bundle\OrderBundle\Form\Type\CartItemType as BaseCartItemType;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * Class CartItemType
 * @package Asdoria\SyliusConfiguratorPlugin\Form\Type
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class CartItemType extends BaseCartItemType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        ['configurator' => $configurator, 'group_by_variant' => $groupByVariant] = $options;

        $builder
            ->addEventSubscriber(new OrderItemAttributeValueTypeFormSubscriber())
            ->remove('quantity');

        $builder
            ->add('quantity', HiddenType::class, ['data' => 1])
            ->add('variant', ChoiceType::class, [
                'required'      => true,
                'constraints'   => [new NotBlank(['groups' => ['sylius']])],
                'label'         => false,
                'expanded'      => true,
                'choice_value'  => 'id',
                'choice_loader' => $this->callbackChoiceLoader($options),
                'block_prefix'  => $groupByVariant ?
                    'asdoria_configurator_order_item_variant_group_by_product' :
                    'asdoria_configurator_order_item_variant',
                'choice_attr'   => $this->callbackChoiceAttr($options),
                'group_by'      => $groupByVariant ? $this->callbackGroupBy() : null
            ]);
    }

    protected function callbackGroupBy(): \Closure
    {
        return function ($choice, $key, $value) {
            if (!$choice instanceof ProductVariantInterface) {
                return null;
            }

            return $choice->getProduct()->getName();
        };
    }

    /**
     * @return CallbackChoiceLoader
     */
    protected function callbackChoiceAttr(array $options): \Closure
    {
        return function (?ProductVariantInterface $variant) {

            /** @var ProductInterface $product */
            $image = $variant?->getImagesByType('thumbnail');
            if (!$image instanceof ImageInterface) {
                $product = $variant?->getProduct();
                $image   = $product?->getImagesByType('thumbnail');
            }

            if (!$image instanceof ImageInterface) {
                $image = $product->getImages()->first() ?? $variant->getImages()->first();
            }
            $attr = [
                'image_path' => $image instanceof ImageInterface ? $image?->getPath(): null,
                'code'       => $product->isSimple() ? $product->getCode() : $variant->getCode(),
                'name'       => $product->isSimple() ? $product->getName() : $variant->getName(),
                'rating'     => $product->getAverageRating(),
            ];

            return $attr;
        };
    }

    /**
     * @return CallbackChoiceLoader
     */
    protected function callbackChoiceLoader(array $options): CallbackChoiceLoader
    {
        return new CallbackChoiceLoader(function () use ($options) {
            ['configurator' => $configurator] = $options;
            return array_reduce(
                $configurator->getProducts()->toArray(),
                function (array $acc, ProductInterface $product) {
                    foreach ($product->getEnabledVariants() as $variant) {
                        $acc[] = $variant;
                    }
                    return $acc;
                },
                []
            );
        });
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver
            ->setDefined([
                'configurator',
                'group_by_variant',
            ])
            ->setDefaults(['group_by_variant' => false])
            ->setAllowedTypes('configurator', ConfiguratorInterface::class)
            ->setAllowedTypes('group_by_variant', 'bool');
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'asdoria_configurator_cart_item';
    }
}
