<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Menu;

use Asdoria\SyliusConfiguratorPlugin\Event\ConfiguratorMenuBuilderEvent;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\LegacyEventDispatcherProxy;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as ContractsEventDispatcherInterface;

class ConfiguratorFormMenuBuilder
{
    public const EVENT_NAME = 'asdoria_product.menu.admin.configurator.form';

    /** @var FactoryInterface */
    private $factory;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(FactoryInterface $factory, EventDispatcherInterface $eventDispatcher)
    {
        $this->factory = $factory;

        if (class_exists('Symfony\Component\EventDispatcher\LegacyEventDispatcherProxy')) {
            /**
             * It could return null only if we pass null, but we pass not null in any case
             *
             * @var ContractsEventDispatcherInterface
             */
            $eventDispatcher = LegacyEventDispatcherProxy::decorate($eventDispatcher);
        }

        $this->eventDispatcher = $eventDispatcher;
    }

    public function createMenu(array $options = []): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        if (!array_key_exists('configurator', $options) || !$options['configurator'] instanceof ConfiguratorInterface) {
            return $menu;
        }

        ['configurator' => $configurator] = $options;
        $menu
            ->addChild('details')
            ->setAttribute('template', '@AsdoriaSyliusConfiguratorPlugin/Admin/Configurator/Tab/_details.html.twig')
            ->setLabel('sylius.ui.details')
            ->setCurrent(true);

        $menu
            ->addChild('products')
            ->setAttribute('template', '@AsdoriaSyliusConfiguratorPlugin/Admin/Configurator/Tab/_products.html.twig')
            ->setLabel('asdoria.ui.configurators_step_1')
        ;
        $menu
            ->addChild('calculator')
            ->setAttribute('template', '@AsdoriaSyliusConfiguratorPlugin/Admin/Configurator/Tab/_calculator.html.twig')
            ->setLabel('asdoria.ui.calculator')
        ;

        $menu
            ->addChild('media')
            ->setAttribute('template', '@AsdoriaSyliusConfiguratorPlugin/Admin/Configurator/Tab/_media.html.twig')
            ->setLabel('sylius.ui.media')
        ;

        if (class_exists('Symfony\Component\EventDispatcher\LegacyEventDispatcherProxy')) {
            $this->eventDispatcher->dispatch(
                new ConfiguratorMenuBuilderEvent($this->factory, $menu, $options['configurator']),
                self::EVENT_NAME
            );
        } else {
            $this->eventDispatcher->dispatch(
                new ConfiguratorMenuBuilderEvent($this->factory, $menu, $options['configurator']),
                self::EVENT_NAME
            );
        }

        return $menu;
    }
}
