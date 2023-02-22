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

namespace Asdoria\SyliusConfiguratorPlugin\Form\EventSubscriber;

use Asdoria\SyliusConfiguratorPlugin\Controller\Shop\ConfiguratorAddToCartCommandInterface;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\OrderItemAttributeValueType;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\AttributeValuesAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorStepAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\OrderItemAttributeValueInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Model\CodeAwareInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Valid;

class OrderItemAttributeValueTypeFormSubscriber implements EventSubscriberInterface
{

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SET_DATA => 'postSetData',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function postSetData(FormEvent $event): void
    {
        $form                         = $event->getForm();
        $item                         = $form->getData();
        $configuratorAddToCartCommand = $form->getRoot()?->getData();

        if(
            !$item instanceof ConfiguratorAwareInterface ||
            !$item->getConfigurator() instanceof ConfiguratorInterface ||
            !$configuratorAddToCartCommand instanceof ConfiguratorAddToCartCommandInterface
        ) return;

        $configurator    = $item->getConfigurator();
        $activeSteps     = $configurator->getActiveSteps($configuratorAddToCartCommand->getStep());
        $attributeValues = $configurator->getProductAttributeValuesWithStep($item)->filter(
            fn(OrderItemAttributeValueInterface  $attrValue) => $activeSteps->contains($attrValue->getConfiguratorStep())
        );

        $form->add('attributes', CollectionType::class, [
                'entry_type'   => OrderItemAttributeValueType::class,
                'prototype'    => false,
                'allow_add'    => false,
                'allow_delete' => false,
                'by_reference' => false,
                'label'        => false,
                'constraints'  => [new Valid()],
                'data'         => $attributeValues,
                'entry_options' => [
                    'configurator' => $configurator,
                ],
            ]
        );
    }
}
