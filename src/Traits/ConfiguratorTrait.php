<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits;


use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;

/**
 *
 */
trait ConfiguratorTrait
{
    /**
     * @var ConfiguratorInterface|null
     */
    protected ?ConfiguratorInterface $configurator;

    /**
     * @return ConfiguratorInterface|null
     */
    public function getConfigurator(): ?ConfiguratorInterface
    {
        return $this->configurator;
    }

    /**
     * @param ConfiguratorInterface|null $configurator
     */
    public function setConfigurator(?ConfiguratorInterface $configurator): void
    {
        $this->configurator = $configurator;
    }
}
