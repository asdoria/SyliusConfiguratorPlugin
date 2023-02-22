<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Form\Type\Calculator\Trait;

use Asdoria\SyliusConfiguratorPlugin\Calculator\Model\CalculatorInterface;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\CalculatorChoiceType;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\ConfiguratorItemProductAttributeType;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\ConfiguratorItemType;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\ConfiguratorType;
use Asdoria\SyliusConfiguratorPlugin\Form\EventSubscriber\CalculatorTypeFormSubscriber;
use Asdoria\SyliusConfiguratorPlugin\Traits\CalculatorRegistryTrait;
use Asdoria\SyliusConfiguratorPlugin\Traits\FormTypeRegistryTrait;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Class CalculatorTypeExtension
 * @package Asdoria\SyliusConfiguratorPlugin\Form\Extension
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
trait CalculatorTypeTrait
{
    use CalculatorRegistryTrait;
    use FormTypeRegistryTrait;

    /**
     * {@inheritdoc}
     */
    public function buildCalculatorForm(FormBuilderInterface $builder, array $options): void
    {
        if (empty($this->formTypeRegistry) || empty($this->calculatorRegistry)) {
            return;
        }

        $type = $options['calculator_choice_type'] ?? CalculatorChoiceType::class;
        $builder->add('calculator', $type, [
            'label' => 'asdoria.form.configurator.calculator',
            'required' => false
        ]);

        $builder->addEventSubscriber(
            new CalculatorTypeFormSubscriber(
                $this->formTypeRegistry,
                $this->calculatorRegistry,
            )
        );

        $this->buildPrototypes($builder);
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return void
     */
    protected function buildPrototypes(FormBuilderInterface $builder): void {
        $prototypes = [];
        foreach ($this->calculatorRegistry->all() as $name => $calculator) {
            /** @var CalculatorInterface $calculator */
            $calculatorType = $calculator->getType();

            if (!$this->formTypeRegistry->has($calculatorType, 'default')) {
                continue;
            }

            $form = $builder->create('configuration', $this->formTypeRegistry->get($calculatorType, 'default'));

            $prototypes['calculators'][$name] = $form->getForm();
        }

        $builder->setAttribute('prototypes', $prototypes);
    }

    /**
     * @psalm-suppress MissingPropertyType
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (!$form->getConfig()->hasAttribute('prototypes')) {
            return;
        }
        
        $view->vars['rules_help'] = $options['rules_help'] ?? '';

        $view->vars['prototypes'] = [];

        foreach ($form->getConfig()->getAttribute('prototypes') as $group => $prototypes) {
            foreach ($prototypes as $type => $prototype) {
                $view->vars['prototypes'][$group . '_' . $type] = $prototype->createView($view);
            }
        }
    }
}
