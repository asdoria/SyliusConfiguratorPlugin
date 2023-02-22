<?php


declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Form\Type;

use Asdoria\SyliusConfiguratorPlugin\Entity\Configurator;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Asdoria\SyliusConfiguratorPlugin\Repository\Model\ConfiguratorRepositoryInterface;
use Sylius\Bundle\AttributeBundle\Form\Type\AttributeChoiceType;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ConfiguratorAttributeChoiceType extends AttributeChoiceType
{
    /**
     * @param ConfiguratorRepositoryInterface $configuratorRepository
     * @param RequestStack                    $requestStack
     * @param ChannelContextInterface         $channelContext
     */
    public function __construct(
        protected ConfiguratorRepositoryInterface $configuratorRepository,
        protected RequestStack $requestStack,
        protected ChannelContextInterface $channelContext
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['multiple']) {
            $builder->addModelTransformer(new CollectionToArrayTransformer());
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'choices' => function (Options $options) {
                    $slug = $this->requestStack->getMainRequest()->attributes->get('slug', null);
                    $locale = $this->requestStack->getMainRequest()->attributes->get('_locale', null);
                    $channel = $this->channelContext->getChannel();
                    $configurator = $this->configuratorRepository->findOneBySlug($slug, $locale, $channel);
                    return $configurator->getProductAttributes();
                },
                'choice_value' => 'code',
                'choice_label' => 'name',
                'choice_translation_domain' => false,
            ])
        ;
    }
    public function getBlockPrefix(): string
    {
        return 'asdoria_configurator_attribute_choice';
    }
}
