<?php


declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorStepInterface as StepInterface;

/**
 * Trait ConfiguratorStepsTrait
 * @package Asdoria\SyliusConfiguratorPlugin\Traits
 */
trait ConfiguratorStepsTrait
{
    protected Collection $steps;

    public function initializeConfiguratorStepsCollection()
    {
        $this->steps = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    /**
     * {@inheritdoc}
     */
    public function getStepsByType(string $type): Collection
    {
        return $this->steps->filter(function (StepInterface $step) use ($type) {
            return $type === $step->getType();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function hasSteps(): bool
    {
        return !$this->steps->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function hasStep(StepInterface $step): bool
    {
        return $this->steps->contains($step);
    }

    /**
     * {@inheritdoc}
     */
    public function addStep(StepInterface $step): void
    {
        if (false === $this->hasStep($step)) {
            $step->setOwner($this);
            $this->steps->add($step);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeStep(StepInterface $step): void
    {
        if ($this->hasStep($step)) {
            $step->setOwner(null);
            $this->steps->removeElement($step);
        }
    }

}
