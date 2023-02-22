<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Form\EventSubscriber;

use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorItemAdditionalProductTranslation;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\ConfiguratorItemAdditionalProductTranslationType;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\ConfiguratorItemTranslationType;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ProductAttributeAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ProductAwareInterface;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAttributeChoiceType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAutocompleteChoiceType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ItemAdditionalProductTypeFormSubscriber
 * @package Asdoria\SyliusConfiguratorPlugin\Form\Type
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ItemAdditionalProductTypeFormSubscriber  implements EventSubscriberInterface
{

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData'
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event): void
    {
        if (!$event->getData() instanceof ProductAwareInterface) {
            return;
        }

        if($event->getForm()->has('calculator')) {
            $event->getForm()->remove('calculator');
        }
        $event->getForm()->add('product', ProductAutocompleteChoiceType::class, [
            'label' => 'asdoria.form.configurator_item_additional_product.product',
            'constraints' => [new NotBlank(['groups' => ['sylius', 'asdoria_configurator_plugin']])]
        ]);
    }

}
