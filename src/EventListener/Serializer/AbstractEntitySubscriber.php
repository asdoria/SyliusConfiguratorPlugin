<?php

declare(strict_types=1);


namespace Asdoria\SyliusConfiguratorPlugin\EventListener\Serializer;


use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Metadata\StaticPropertyMetadata;
use JMS\Serializer\Metadata\VirtualPropertyMetadata;

/**
 * Class AbstractEntitySubscriber
 * @package Asdoria\SyliusConfiguratorPlugin\EventListener\Serializer
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
abstract class AbstractEntitySubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            ['event' => Events::POST_SERIALIZE, 'method' => 'onPostSerialize', 'priority' => 500, 'class' => static::getClassName()],
        ];
    }

    public function onPostSerialize(ObjectEvent $event): void
    {
        foreach ($this->getMethodNames() as $methodName => $groups) {
            $visitor  = $event->getVisitor();

            $metadata = new VirtualPropertyMetadata(static::getClassName(), $methodName);
            $availableGroups = array_intersect($groups, $event->getContext()->getAttribute('groups'));
            if (!$visitor->hasData($metadata->name) && !empty($availableGroups)) {
                $value = $this->{$methodName}($event->getObject());
                $visitor->visitProperty(
                    new StaticPropertyMetadata(static::getClassName(), $metadata->name, $value),
                    $value
                );
            }
        }
    }

    abstract protected static function getClassName(): string;

    abstract protected function getMethodNames(): array;
}
