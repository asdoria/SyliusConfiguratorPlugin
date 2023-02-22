<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Form\EventSubscriber;

use Asdoria\SyliusConfiguratorPlugin\Calculator\Model\CalculatorInterface;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\CalculatorChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Builder\DefaultFormBuilderInterface;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Sylius\Component\Registry\ServiceRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

/**
 * Class CalculatorTypeFormSubscriber
 * @package Asdoria\SyliusConfiguratorPlugin\Form\EventSubscriber
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class CalculatorTypeFormSubscriber implements EventSubscriberInterface
{

    public function __construct(
        protected FormTypeRegistryInterface $formTypeRegistry,
        protected ServiceRegistry $calculatorRegistry
    )
    {
    }

    /**
     * {@inheritdoc}
     * sylius.form_builder.default
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
     *
     * @return void
     */
    public function preSetData(FormEvent $event): void {
        $entity = $event->getData();

        if (null === $entity || null === $entity->getId() || !method_exists($entity, 'getCalculator')) {
            return;
        }

        $calculatorName = !empty($entity->getCalculator()) ? $entity->getCalculator() : null;

        if(empty($calculatorName)) {
            return;
        }

        $this->addConfigurationField($event->getForm(), $calculatorName);
    }


    /**
     * @param FormEvent $event
     *
     * @return void
     */
    public function preSubmit(FormEvent $event): void {
        $data = $event->getData();

        if (empty($data) || !array_key_exists('calculator', $data) || empty($data['calculator'])) {
            return;
        }

        $this->addConfigurationField($event->getForm(), $data['calculator']);
    }

    /**
     * @param FormInterface $form
     * @param string        $calculatorName
     *
     * @return void
     */
    private function addConfigurationField(FormInterface $form, string $calculatorName): void
    {
        /** @var CalculatorInterface $calculator */
        $calculator = $this->calculatorRegistry->get($calculatorName);

        $calculatorType = $calculator->getType();

        if (!$this->formTypeRegistry->has($calculatorType, 'default')) {
            return;
        }

        $form->add('configuration', $this->formTypeRegistry->get($calculatorType, 'default'));
    }
}
