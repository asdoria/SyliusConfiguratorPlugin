<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    /**
     * @param MenuBuilderEvent $event
     */
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $item = $menu->getChild('catalog');

        $item
            ->addChild('product_configurators', [
                'route' => 'asdoria_admin_configurator_index',
            ])
            ->setLabel('asdoria.ui.configurators')
            ->setLabelAttribute('icon', 'list ol');
    }

}
