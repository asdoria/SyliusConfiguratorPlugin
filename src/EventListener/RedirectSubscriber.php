<?php


declare(strict_types=1);


namespace Asdoria\SyliusConfiguratorPlugin\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class RedirectSubscriber
 * @package Asdoria\SyliusConfiguratorPlugin\EventListener
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class RedirectSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse'],
        ];
    }

    private Session $session;

    /**
     * @param Session $session
     */
    public function __construct (
        RequestStack $requestStack
    ) {
        $this->session = $requestStack->getSession();
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (!$request->attributes->has(ArchivedCheckListener::_REDIRECT_ATTRIBUTE)) {
            return;
        }

        $redirectResponse = $request->attributes->get(ArchivedCheckListener::_REDIRECT_ATTRIBUTE);
        if ($redirectResponse instanceof RedirectResponse) {
            $event->setResponse($redirectResponse);
            $this->session->getFlashBag()->add('info', 'asdoria.ui.you_are_been_redirect_page_archived');
        }
    }
}
