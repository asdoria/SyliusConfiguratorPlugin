<?php


declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\OrderProcessor;

use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Asdoria\SyliusConfiguratorPlugin\Traits\ConfiguratorCalculatorTrait;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Order\Model\OrderInterface as BaseOrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

class OrderPricesRecalculator implements OrderProcessorInterface
{
    use ConfiguratorCalculatorTrait;

    public function __construct(protected OrderProcessorInterface $inner)
    {
    }

    public function process(BaseOrderInterface $order): void
    {
        $this->inner->process($order);

        $configuratorItems = $order->getItems()
            ->filter(fn(OrderItemInterface $item) =>
                $item instanceof ConfiguratorAwareInterface && $item->getConfigurator() instanceof ConfiguratorInterface
            );

        /** @var ConfiguratorAwareInterface $item */
        foreach ($configuratorItems as $item) {
            if ($item->isImmutable()) {
                continue;
            }

            $item->setUnitPrice($this->configuratorCalculator->calculate(
                $item
            ));
        }
    }
}
