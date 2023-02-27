<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\DependencyInjection;

use Asdoria\SyliusConfiguratorPlugin\Controller\ConfiguratorItemController;
use Asdoria\SyliusConfiguratorPlugin\Entity\Configurator;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorImage;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorItem;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorItemAdditionalProduct;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorItemAdditionalProductTranslation;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorItemImage;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorItemProductAttribute;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorItemProductAttributeTranslation;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorItemTranslation;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorStep;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorStepImage;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorStepTranslation;
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorTranslation;
use Asdoria\SyliusConfiguratorPlugin\Entity\OrderItemAttributeValue;
use Asdoria\SyliusConfiguratorPlugin\Factory\ConfiguratorImageFactory;
use Asdoria\SyliusConfiguratorPlugin\Factory\ConfiguratorItemFactory;
use Asdoria\SyliusConfiguratorPlugin\Factory\ConfiguratorItemImageFactory;
use Asdoria\SyliusConfiguratorPlugin\Factory\ConfiguratorStepImageFactory;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\ConfiguratorImageType;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\ConfiguratorItemAdditionalProductType;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\ConfiguratorItemImageType;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\ConfiguratorItemProductAttributeType;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\ConfiguratorItemTranslationType;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\ConfiguratorItemType;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\ConfiguratorStepImageType;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\ConfiguratorStepTranslationType;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\ConfiguratorStepType;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\ConfiguratorTranslationType;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\ConfiguratorType;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\OrderItem\OrderItemAttributeValueType;
use Asdoria\SyliusConfiguratorPlugin\Repository\ConfiguratorItemRepository;
use Asdoria\SyliusConfiguratorPlugin\Repository\ConfiguratorRepository;
use Asdoria\SyliusConfiguratorPlugin\Repository\ConfiguratorStepRepository;
use Asdoria\SyliusConfiguratorPlugin\Repository\OrderItemAttributeValueRepository;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


/**
 * Class Configuration
 * @package Asdoria\SyliusConfiguratorPlugin\DependencyInjection
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    const _DEFAULT_PATH_SHOP_NAME = 'configurators';
    /**
     * @return TreeBuilder|void
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('asdoria_sylius_configurator');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
            ->scalarNode('path_shop_name')->defaultValue(Configuration::_DEFAULT_PATH_SHOP_NAME)->end()
            ->end()
        ;

        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addResourcesSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('configurator')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Configurator::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(ConfiguratorRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->scalarNode('form')->defaultValue(ConfiguratorType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('translation')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->variableNode('options')->end()
                                        ->arrayNode('classes')
                                        ->addDefaultsIfNotSet()
                                        ->children()
                                            ->scalarNode('model')->defaultValue(ConfiguratorTranslation::class)->cannotBeEmpty()->end()
                                            ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                            ->scalarNode('repository')->cannotBeEmpty()->end()
                                            ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                            ->scalarNode('form')->defaultValue(ConfiguratorTranslationType::class)->cannotBeEmpty()->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('configurator_step')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->variableNode('options')->end()
                            ->arrayNode('classes')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('model')->defaultValue(ConfiguratorStep::class)->cannotBeEmpty()->end()
                                    ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                    ->scalarNode('repository')->defaultValue(ConfiguratorStepRepository::class)->cannotBeEmpty()->end()
                                    ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                    ->scalarNode('form')->defaultValue(ConfiguratorStepType::class)->cannotBeEmpty()->end()
                                ->end()
                            ->end()
                            ->arrayNode('translation')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->variableNode('options')->end()
                                    ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(ConfiguratorStepTranslation::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->scalarNode('form')->defaultValue(ConfiguratorStepTranslationType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->end()
                    ->arrayNode('configurator_item')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->variableNode('options')->end()
                            ->arrayNode('classes')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('model')->defaultValue(ConfiguratorItem::class)->cannotBeEmpty()->end()
                                    ->scalarNode('controller')->defaultValue(ConfiguratorItemController::class)->cannotBeEmpty()->end()
                                    ->scalarNode('repository')->defaultValue(ConfiguratorItemRepository::class)->cannotBeEmpty()->end()
                                    ->scalarNode('factory')->defaultValue(ConfiguratorItemFactory::class)->end()
                                    ->scalarNode('form')->defaultValue(ConfiguratorItemType::class)->cannotBeEmpty()->end()
                                ->end()
                            ->end()
                            ->arrayNode('translation')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->variableNode('options')->end()
                                    ->arrayNode('classes')
                                        ->addDefaultsIfNotSet()
                                        ->children()
                                            ->scalarNode('model')->defaultValue(ConfiguratorItemTranslation::class)->cannotBeEmpty()->end()
                                            ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                            ->scalarNode('repository')->cannotBeEmpty()->end()
                                            ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                            ->scalarNode('form')->defaultValue(ConfiguratorItemTranslationType::class)->cannotBeEmpty()->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('configurator_image')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->variableNode('options')->end()
                            ->arrayNode('classes')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('model')->defaultValue(ConfiguratorImage::class)->cannotBeEmpty()->end()
                                    ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                    ->scalarNode('repository')->cannotBeEmpty()->end()
                                    ->scalarNode('factory')->defaultValue(ConfiguratorImageFactory::class)->end()
                                    ->scalarNode('form')->defaultValue(ConfiguratorImageType::class)->cannotBeEmpty()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('configurator_step_image')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->variableNode('options')->end()
                            ->arrayNode('classes')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('model')->defaultValue(ConfiguratorStepImage::class)->cannotBeEmpty()->end()
                                    ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                    ->scalarNode('repository')->cannotBeEmpty()->end()
                                    ->scalarNode('factory')->defaultValue(ConfiguratorStepImageFactory::class)->end()
                                    ->scalarNode('form')->defaultValue(ConfiguratorStepImageType::class)->cannotBeEmpty()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('configurator_item_image')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->variableNode('options')->end()
                            ->arrayNode('classes')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('model')->defaultValue(ConfiguratorItemImage::class)->cannotBeEmpty()->end()
                                    ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                    ->scalarNode('repository')->cannotBeEmpty()->end()
                                    ->scalarNode('factory')->defaultValue(ConfiguratorItemImageFactory::class)->end()
                                    ->scalarNode('form')->defaultValue(ConfiguratorItemImageType::class)->cannotBeEmpty()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('order_item_attribute_value')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->variableNode('options')->end()
                            ->arrayNode('classes')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('model')->defaultValue(OrderItemAttributeValue::class)->cannotBeEmpty()->end()
                                    ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                    ->scalarNode('repository')->defaultValue(OrderItemAttributeValueRepository::class)->cannotBeEmpty()->end()
                                    ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                    ->scalarNode('form')->defaultValue(OrderItemAttributeValueType::class)->cannotBeEmpty()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('configurator_item_product_attribute')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->variableNode('options')->end()
                            ->arrayNode('classes')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('model')->defaultValue(ConfiguratorItemProductAttribute::class)->cannotBeEmpty()->end()
                                    ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                    ->scalarNode('repository')->cannotBeEmpty()->end()
                                    ->scalarNode('factory')->defaultValue(ConfiguratorItemFactory::class)->end()
                                    ->scalarNode('form')->defaultValue(ConfiguratorItemProductAttributeType::class)->cannotBeEmpty()->end()
                                ->end()
                            ->end()
//                            ->arrayNode('translation')
//                                ->addDefaultsIfNotSet()
//                                ->children()
//                                    ->variableNode('options')->end()
//                                    ->arrayNode('classes')
//                                        ->addDefaultsIfNotSet()
//                                        ->children()
//                                            ->scalarNode('model')->defaultValue(ConfiguratorItemTranslation::class)->cannotBeEmpty()->end()
//                                            ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
//                                            ->scalarNode('repository')->cannotBeEmpty()->end()
//                                            ->scalarNode('factory')->defaultValue(Factory::class)->end()
//                                            ->scalarNode('form')->defaultValue(ConfiguratorItemTranslationType::class)->cannotBeEmpty()->end()
//                                        ->end()
//                                    ->end()
//                                ->end()
//                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('configurator_item_additional_product')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->variableNode('options')->end()
                            ->arrayNode('classes')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('model')->defaultValue(ConfiguratorItemAdditionalProduct::class)->cannotBeEmpty()->end()
                                    ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                    ->scalarNode('repository')->cannotBeEmpty()->end()
                                    ->scalarNode('factory')->defaultValue(ConfiguratorItemFactory::class)->end()
                                    ->scalarNode('form')->defaultValue(ConfiguratorItemAdditionalProductType::class)->cannotBeEmpty()->end()
                                ->end()
                            ->end()
//                            ->arrayNode('translation')
//                                ->addDefaultsIfNotSet()
//                                ->children()
//                                    ->variableNode('options')->end()
//                                    ->arrayNode('classes')
//                                        ->addDefaultsIfNotSet()
//                                        ->children()
//                                            ->scalarNode('model')->defaultValue(ConfiguratorItemTranslation::class)->cannotBeEmpty()->end()
//                                            ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
//                                            ->scalarNode('repository')->cannotBeEmpty()->end()
//                                            ->scalarNode('factory')->defaultValue(Factory::class)->end()
//                                            ->scalarNode('form')->defaultValue(ConfiguratorItemTranslationType::class)->cannotBeEmpty()->end()
//                                        ->end()
//                                    ->end()
//                                ->end()
//                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
