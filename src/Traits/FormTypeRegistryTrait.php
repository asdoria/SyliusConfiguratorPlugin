<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Traits;

use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;

/**
 * Class FormTypeRegistryTrait
 * @package Asdoria\SyliusConfiguratorPlugin\Traits
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
trait FormTypeRegistryTrait
{
    protected ?FormTypeRegistryInterface$formTypeRegistry = null;

    /**
     * @return FormTypeRegistryInterface|null
     */
    public function getFormTypeRegistry(): ?FormTypeRegistryInterface
    {
        return $this->formTypeRegistry;
    }

    /**
     * @param FormTypeRegistryInterface|null $formTypeRegistry
     */
    public function setFormTypeRegistry(?FormTypeRegistryInterface $formTypeRegistry): void
    {
        $this->formTypeRegistry = $formTypeRegistry;
    }
    
    
}
