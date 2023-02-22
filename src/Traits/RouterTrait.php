<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits;

use Symfony\Component\Routing\RouterInterface;

trait RouterTrait
{
    protected RouterInterface $router;

    /**
     * @return RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    /**
     * @param RouterInterface $router
     */
    public function setRouter(RouterInterface $router): void
    {
        $this->router = $router;
    }
}
