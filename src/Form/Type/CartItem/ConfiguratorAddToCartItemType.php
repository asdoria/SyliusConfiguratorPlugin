<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Form\Type\CartItem;

use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorStep;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorItemAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Asdoria\SyliusConfiguratorPlugin\Traits\ChannelContextTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\CurrencyContextTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\MoneyFormatterTrait;
use Sylius\Bundle\ProductBundle\Form\Type\ProductVariantChoiceType;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Bundle\OrderBundle\Form\Type\CartItemType;
use Sylius\Bundle\ResourceBundle\Form\DataTransformer\ResourceToIdentifierTransformer;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sylius\Component\Product\Model\ProductVariantInterface;

/**
 * Class ConfiguratorAddToCartItemType
 * @package Asdoria\SyliusConfiguratorPlugin\Form\Type\CartItem
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorAddToCartItemType extends AbstractResourceType
{
    use MoneyFormatterTrait;
    use ChannelContextTrait;
    use CurrencyContextTrait;
    /**
     * @param string   $dataClass FQCN
     * @param string[] $validationGroups
     */
    public function __construct(string $dataClass, EntityRepository $variantRepository, array $validationGroups = [])
    {
        parent::__construct($dataClass, $validationGroups);
        $this->variantRepository = $variantRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, $this->preSetData());
        $builder->addEventListener(FormEvents::POST_SET_DATA, $this->postSetData());
    }

    protected function preSetData(): \Closure
    {
        return function (FormEvent $event): void {
            $data    = $event->getData();
            $product = $data?->getConfiguratorItem()?->getProduct();
            $step    = $data?->getConfiguratorItem()?->getConfiguratorStep();
            $event->getForm()
                ->add(
                    'cartItem',
                    CartItemType::class,
                    [
                        'label'   => false,
                        'product' => $product,
                        'attr'    => ['step' => $step?->getCode()]
                    ]
                );
        };
    }

    protected function postSetData(): \Closure
    {
        return function (FormEvent $event): void {
            $cartItemForm = $event->getForm()->get('cartItem');
            $cartItemForm
                ->remove('quantity')
                ->add('quantity', HiddenType::class, ['data' => 1]);

            if ($cartItemForm->has('variant')) {
                $cartItemForm->remove('variant');
            }

            $item    = $event->getForm()->getData()?->getConfiguratorItem();

            $cartItemForm
                ->add('variant', ProductVariantChoiceType::class, [
                'label'        => $item?->getName(),
                'required'     => false,
                'choice_label' => fn(ProductVariantInterface $variant): string => $variant->getCode(),
                'product'      => $item?->getProduct(),
                'choice_attr'  => $this->callbackChoiceAttr(),
                'choice_value' => 'id',
                'block_prefix' => 'asdoria_configurator_order_item_variant',
            ]);
        };
    }

    /**
     * @return CallbackChoiceLoader
     */
    protected function callbackChoiceAttr(): \Closure
    {
        return function (?ProductVariantInterface $variant) {
            /** @var ProductInterface $product */
            $product = $variant?->getProduct();
            $image   = $variant?->getImages()->first();
            if (!$image instanceof ImageInterface) {
                $image = $product->getImagesByType('thumbnail')->first();
            }
            $name = $product->isSimple() ? $product->getName(): $variant->getName();
            /** @var \Sylius\Component\Core\Model\ProductVariant $variant */
            $amount = $variant->getChannelPricingForChannel($this->channelContext->getChannel())?->getPrice();
            $price  = $this->moneyFormatter->format($amount ?? 0, $this->currencyContext->getCurrencyCode());
            $attr = [
                'image_path' => $image instanceof ImageInterface ? $image?->getPath(): null,
                'code'  => $product->isSimple() ? $product->getCode(): $variant->getCode(),
                'name'  => sprintf('%s (+%s)',$name, $price),
                'rating'=> $product->getAverageRating(),
            ];

            return $attr;
        };
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'asdoria_configurator_add_to_cart_item';
    }
}
