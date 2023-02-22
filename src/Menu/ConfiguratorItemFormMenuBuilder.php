<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Menu;

use Asdoria\SyliusConfiguratorPlugin\Event\ConfiguratorItemMenuBuilderEvent;
use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ProductAttributeAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemInterface;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\LegacyEventDispatcherProxy;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as ContractsEventDispatcherInterface;

class ConfiguratorItemFormMenuBuilder
{
    public const EVENT_NAME = 'asdoria_product.menu.admin.configurator_item.form';

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

        if (!array_key_exists('configuratorItem', $options) || !$options['configuratorItem'] instanceof ConfiguratorItemInterface) {
            return $menu;
        }

        ['configuratorItem' => $configuratorItem] = $options;
        $menu
            ->addChild('details')
            ->setAttribute('template', '@AsdoriaSyliusConfiguratorPlugin/Admin/ConfiguratorItem/Tab/_details.html.twig')
            ->setLabel('sylius.ui.details')
            ->setCurrent(true);

        if ($configuratorItem instanceof ProductAttributeAwareInterface) {
            $menu
                ->addChild('calculator')
                ->setAttribute('template', '@AsdoriaSyliusConfiguratorPlugin/Admin/ConfiguratorItem/Tab/_calculator.html.twig')
                ->setLabel('asdoria.ui.calculator');
        }
        
        $menu
            ->addChild('media')
            ->setAttribute('template', '@AsdoriaSyliusConfiguratorPlugin/Admin/ConfiguratorItem/Tab/_media.html.twig')
            ->setLabel('sylius.ui.media')
        ;

        if (class_exists('Symfony\Component\EventDispatcher\LegacyEventDispatcherProxy')) {
            $this->eventDispatcher->dispatch(
                new ConfiguratorItemMenuBuilderEvent($this->factory, $menu, $options['configuratorItem']),
                self::EVENT_NAME
            );
        } else {
            $this->eventDispatcher->dispatch(
                new ConfiguratorItemMenuBuilderEvent($this->factory, $menu, $options['configuratorItem']),
                self::EVENT_NAME
            );
        }

        return $menu;
    }
}
