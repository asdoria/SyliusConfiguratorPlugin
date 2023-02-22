<?php

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Form\Type\Calculator;

use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ProductAttributeAwareInterface;
use Asdoria\SyliusConfiguratorPlugin\Repository\Model\ConfiguratorItemRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\FixedCollectionType;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductAttributeChoicesCollectionType extends AbstractType
{
    protected array $choices = [];

    public function __construct(
        protected RequestStack $requestStack,
        protected RepositoryInterface $repository
    ) {

    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entries' => [],
            'entry_name' => fn ($row) => $row,
            'error_bubbling' => false,
            'entry_options' => fn ($row) => ['label' => sprintf('%s (%s)', $this->choices[$row] , $row) ]
        ]);

        $resolver->setNormalizer('entries', function (OptionsResolver $options, $value) {
            $currentRequest = $this->requestStack->getCurrentRequest();
            $attributes     = $currentRequest->attributes;
            $routeParams    = $attributes->has('_route_params') ? $attributes->get('_route_params') : [];

            try {
                ['id' =>  $id] = $routeParams;
                /** @var ProductAttributeAwareInterface $item */
                $item = $this->repository->findOneBy(['id' =>  $id]);
                if ($item->getProductAttribute()->getStorageType() !== AttributeValueInterface::STORAGE_JSON) {
                    throw new \InvalidArgumentException('This attribute is not a valid Storage type for this calculator');
                }
                $defaultLocale = $item->getProductAttribute()->getTranslation()->getLocale();
                $this->choices =
                    array_map(
                        fn($row) => $row[$defaultLocale] ?? null,
                        $item->getProductAttribute()->getConfiguration()['choices'] ?? []
                    );
            } catch (\Throwable $exception) {
                return [];
            }
            return array_flip($this->choices);
        });
    }

    public function getParent(): string
    {
        return FixedCollectionType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'asdoria_configurator_plugin_calculator_configurator_item_choice_collection';
    }
}
