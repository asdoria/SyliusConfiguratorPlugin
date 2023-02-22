<?php

namespace Asdoria\SyliusConfiguratorPlugin\EventListener;

use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorSimilarInterface;
use Asdoria\SyliusConfiguratorPlugin\Repository\Model\ConfiguratorRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ArchivedCheckListener
 * @package Asdoria\SyliusConfiguratorPlugin\EventListener
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ArchivedCheckListener
{
    const _CHECKED_ROUTE = 'asdoria_product_shop_configurator_show';
    const _REDIRECT_ATTRIBUTE = 'asdoria_configurator_redirect_archived';

    /**
     * @param ConfiguratorRepositoryInterface $configuratorRepository
     * @param LocaleContextInterface           $localeContext
     * @param ChannelContextInterface          $channelContext
     * @param UrlGeneratorInterface            $router
     * @param RequestStack                     $requestStack
     */
    public function __construct (
        protected ConfiguratorRepositoryInterface $configuratorRepository,
        protected LocaleContextInterface $localeContext,
        protected ChannelContextInterface $channelContext,
        protected UrlGeneratorInterface $router,
        protected RequestStack $requestStack
    )
    {
    }

    /**
     * @param ResourceControllerEvent $event
     * @return void
     */
    public function onCheck(ResourceControllerEvent $event): void
    {
        $request = $this->requestStack->getMainRequest();
        $routeName = $request->attributes->get('_route');

        if ($routeName !== self::_CHECKED_ROUTE || !$request->attributes->has('slug')) {
            return;
        }

        $slug     = $request->attributes->get('slug');
        $resource = $this->productConfiguratorRepository->findOneBySlug(
            $slug,
            $this->localeContext->getLocaleCode(),
            $this->channelContext->getChannel()
        );

        if (!$resource instanceof ConfiguratorInterface) {
            return;
        }

        if (!$resource->isEnabled()) {
            return;
        }

        try {
            if($resource->getArchivedAt() !== null) {
                throw new \InvalidArgumentException('Configurator is archived');
            }
        } catch (\Throwable $exception) {
            $request->attributes->set(self::_REDIRECT_ATTRIBUTE, $this->getRedirectResponse($resource, $request->headers->get('referer')));
        }
    }


    /**
     * @param ConfiguratorInterface $configurator
     * @param string|null            $referer
     *
     * @return RedirectResponse
     */
    private function getRedirectResponse(ConfiguratorInterface $configurator, ?string $referer): RedirectResponse
    {
        if (!$configurator->getEnabledSimilarConfigurators($this->channelContext->getChannel())->isEmpty()) {
            /** @var ConfiguratorSimilarInterface $similarConfigurator */
            $similarConfigurator = $configurator->getEnabledSimilarConfigurators($this->channelContext->getChannel())->first();
            return new RedirectResponse(
                $this->router->generate(self::_CHECKED_ROUTE, ['slug'=> $similarConfigurator->getSimilarConfigurator()->getSlug()])
            );
        }

        if (null !== $referer) {
            return new RedirectResponse($referer);
        }

        return new RedirectResponse($this->router->generate('sylius_shop_homepage'));
    }


}
