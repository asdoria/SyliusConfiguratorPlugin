<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits;


use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemInterface;

/**
 *
 */
trait ConfiguratorItemTrait
{
    /**
     * @var ConfiguratorItemInterface|null
     */
    protected ?ConfiguratorItemInterface $configuratorItem;

    /**
     * @return ConfiguratorItemInterface|null
     */
    public function getConfiguratorItem(): ?ConfiguratorItemInterface
    {
        return $this->configuratorItem;
    }

    /**
     * @param ConfiguratorItemInterface|null $configuratorItem
     */
    public function setConfiguratorItem(?ConfiguratorItemInterface $configuratorItem): void
    {
        $this->configuratorItem = $configuratorItem;
    }
}
