<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Form\Type\CartItem;

use AppBundle\Form\Type\Cart\CartItemChildrenType;
use Asdoria\SyliusConfiguratorPlugin\Context\Model\ConfiguratorContextInterface;
use Asdoria\SyliusConfiguratorPlugin\Controller\Shop\ConfiguratorAddToCartCommandInterface;
use Asdoria\SyliusConfiguratorPlugin\Factory\Model\ConfiguratorAddToCartCommandFactoryInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorStepInterface;
use Asdoria\SyliusConfiguratorPlugin\Repository\Model\ConfiguratorStepRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Bundle\OrderBundle\Controller\AddToCartCommand;

use Sylius\Bundle\ResourceBundle\Form\DataTransformer\ResourceToIdentifierTransformer;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Model\OrderItem;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * Class ConfiguratorAddToCartType
 * @package Asdoria\SyliusConfiguratorPlugin\Form\Type\CartItem
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorAddToCartType extends AbstractResourceType
{
    const _BLOCK_PREFIX ='asdoria_configurator_add_to_cart';

    /**
     * @param RequestStack                        $requestStack
     * @param ConfiguratorContextInterface        $configuratorContext
     * @param ConfiguratorStepRepositoryInterface $stepRepository
     * @param string                              $dataClass
     * @param array                               $validationGroups
     */
    public function __construct(
        protected RequestStack $requestStack,
        protected ConfiguratorContextInterface $configuratorContext,
        protected ConfiguratorStepRepositoryInterface $stepRepository,
        string $dataClass,
        array $validationGroups = [])
    {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $configurator = $this->configuratorContext->getConfigurator();
        $builder
            ->add('step', HiddenType::class)
            ->add('cartItem', CartItemType::class, [
                'label' => false,
                'configurator' => $configurator,
                'group_by_variant' => !$configurator->hasOnlySimpleProducts()
            ])
            ->add('additionalItems', CollectionType::class, [
                'entry_type' => ConfiguratorAddToCartItemType::class,
                'label' => false,
                'allow_add' => false,
                'allow_delete' => false,
            ]
        );

        $builder
            ->get('step')->addModelTransformer(
                new ResourceToIdentifierTransformer($this->stepRepository, 'id'),
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'csrf_protection' => function (Options $options) {
                return $this->requestStack->getMainRequest()->getContentType() !== 'json';
            },
        ]);
    }

    public function getBlockPrefix(): string
    {
        return self::_BLOCK_PREFIX;
    }
}
