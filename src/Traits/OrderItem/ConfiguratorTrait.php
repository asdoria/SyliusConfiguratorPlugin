<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits\OrderItem;


use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\OrderItemAttributeValueInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
/**
 *
 */
trait ConfiguratorTrait
{
    /**
     * @ORM\ManyToOne(
     *      targetEntity="Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface",
     *)
     * @var ConfiguratorInterface|null
     */
    protected ?ConfiguratorInterface $configurator= null;

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
