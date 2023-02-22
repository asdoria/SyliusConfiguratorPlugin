<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits;


use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorStepInterface;

/**
 *
 */
trait ConfiguratorStepTrait
{
    /**
     * @var ConfiguratorStepInterface|null
     */
    protected ?ConfiguratorStepInterface $configuratorStep;

    /**
     * @return ConfiguratorStepInterface|null
     */
    public function getConfiguratorStep(): ?ConfiguratorStepInterface
    {
        return $this->configuratorStep;
    }

    /**
     * @param ConfiguratorStepInterface|null $configuratorStep
     */
    public function setConfiguratorStep(?ConfiguratorStepInterface $configuratorStep): void
    {
        $this->configuratorStep = $configuratorStep;
    }
}
