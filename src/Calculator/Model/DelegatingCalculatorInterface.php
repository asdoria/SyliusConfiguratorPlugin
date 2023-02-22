<?php


declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Calculator\Model;

use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorAwareInterface;

interface DelegatingCalculatorInterface
{
    public function calculate(ConfiguratorAwareInterface $subject): int;
}
