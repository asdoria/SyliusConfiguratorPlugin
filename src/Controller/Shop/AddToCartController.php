<?php

namespace Asdoria\SyliusConfiguratorPlugin\Controller\Shop;

use Asdoria\SyliusConfiguratorPlugin\Factory\Model\ConfiguratorAddToCartCommandFactoryInterface;
use Asdoria\SyliusConfiguratorPlugin\Form\Type\CartItem\ConfiguratorAddToCartType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\View\ConfigurableViewHandlerInterface;
use FOS\RestBundle\View\View;
use Sylius\Bundle\CoreBundle\Checkout\CheckoutStateUrlGeneratorInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Order\Model\OrderItemInterface;
use Sylius\Component\Order\Modifier\OrderModifierInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Sylius\Bundle\OrderBundle\Controller\AddToCartCommandInterface;

class AddToCartController
{
    /**
     * @param Environment                                  $twig
     * @param FormFactoryInterface                         $formFactory
     * @param ConfiguratorAddToCartCommandFactoryInterface $configuratorAddToCartCommandFactory
     * @param ValidatorInterface                           $validator
     * @param CheckoutStateUrlGeneratorInterface           $urlGenerator
     * @param OrderModifierInterface                       $orderModifier
     * @param EntityManagerInterface                       $cartManager
     * @param array                                        $validationGroups
     */
    public function __construct(
        protected Environment                                  $twig,
        protected FormFactoryInterface                         $formFactory,
        protected ConfiguratorAddToCartCommandFactoryInterface $configuratorAddToCartCommandFactory,
        protected ValidatorInterface                           $validator,
        protected CheckoutStateUrlGeneratorInterface           $urlGenerator,
        protected OrderModifierInterface                       $orderModifier,
        protected EntityManagerInterface                       $cartManager,
        protected ConfigurableViewHandlerInterface             $restViewHandler,
        protected array                                        $validationGroups,
    )
    {
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(Request $request): Response
    {
        $configuratorAddToCartCommand = $this->configuratorAddToCartCommandFactory
            ->createWithAddToCartItems();

        $configuratorAddToCartCommand->handleFormData(
            $request->request->all(ConfiguratorAddToCartType::_BLOCK_PREFIX)
        );

        $configurator = $configuratorAddToCartCommand->getConfigurator();
        $lastStep     = $configurator->getConfiguratorSteps()->last();
        $form         = $this->formFactory->create(
            ConfiguratorAddToCartType::class,
            $configuratorAddToCartCommand, ['validation_groups' => $this->validationGroups]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $lastStep !== $configuratorAddToCartCommand->getStep()) {
            $form = $this->handleNextStep($configuratorAddToCartCommand);
        }

        if (
            $request->isMethod('POST') &&
            $form->isSubmitted() &&
            $form->isValid()
        ) {
            $this->errorProcessing($form);
            if ($form->getErrors(true, true)->count() == 0) {
                $cart = $configuratorAddToCartCommand->getCart();
                $this->cartManager->persist($cart);
                $this->cartManager->flush();

                if($request->getContentType() === 'json') {
                    return $this->restViewHandler(View::create([], Response::HTTP_CREATED));
                }

                return new RedirectResponse($this->urlGenerator->generateForCart());
            }
        }

        $formErrors = $form->getErrors(true);

        if ($configuratorAddToCartCommand->getCartItem()->getVariant() instanceof ProductVariantInterface)
            $this->orderModifier->addToOrder($configuratorAddToCartCommand->getCart(), $configuratorAddToCartCommand->getCartItem());

        if ($request->getContentType() === 'json') {
            return $this->restViewHandler($this->createView($form, $configuratorAddToCartCommand));
        }

        $unitPrice = $configuratorAddToCartCommand->getCartItem()->getUnitPrice();

        $this->orderModifier->removeFromOrder($configuratorAddToCartCommand->getCart(), $configuratorAddToCartCommand->getCartItem());

        return new Response(
            $this->twig->render('@AsdoriaSyliusConfiguratorPlugin/Shop/Configurator/show.html.twig',
                [
                    'form'         => $form->createView(),
                    'configurator' => $configurator,
                    'unitPrice'    => $unitPrice,
                    'currentStep'  => $configuratorAddToCartCommand->getStep(),
                    'activeSteps'  => $configurator->getActiveSteps($configuratorAddToCartCommand->getStep()),
                    'errors'       => $formErrors
                ]
            )
        );
    }

    /**
     * @param FormInterface                         $form
     * @param ConfiguratorAddToCartCommandInterface $configuratorAddToCartCommand
     *
     * @return View
     *
     */
    protected function createView(FormInterface $form, ConfiguratorAddToCartCommandInterface $configuratorAddToCartCommand):View {
        $errors = $form->getErrors(true);
        $additionalItemsPrice = 0;
        foreach ($configuratorAddToCartCommand->getAddToCartCommandAdditionalItems() as $addToCartCommandAdditionalItem) {
            $this->orderModifier->addToOrder($addToCartCommandAdditionalItem->getCart(), $addToCartCommandAdditionalItem->getCartItem());
            $additionalItemsPrice += $addToCartCommandAdditionalItem->getCartItem()->getTotal();
            $this->orderModifier->removeFromOrder($addToCartCommandAdditionalItem->getCart(), $addToCartCommandAdditionalItem->getCartItem());
        }

        return $errors->count() > 0 ?
            View::create($form, Response::HTTP_BAD_REQUEST)->setData(['errors' => $errors]):
            View::create($form, Response::HTTP_OK)
                ->setData([
                    'unitPrice' => $configuratorAddToCartCommand->getCartItem()->getUnitPrice(),
                    'additionalItemsPrice' => $additionalItemsPrice,
                    'nextStep'  => $configuratorAddToCartCommand->getStep()
                ]);
    }

    /**
     * @param ConfiguratorAddToCartCommandInterface $configuratorAddToCartCommand
     *
     * @return FormInterface
     */
    protected function handleNextStep(ConfiguratorAddToCartCommandInterface $configuratorAddToCartCommand): FormInterface {
        $configuratorAddToCartCommand->nextStep(
            $this->configuratorAddToCartCommandFactory->createWithAddToCartItems()
        );

        return $this->formFactory->create(
            ConfiguratorAddToCartType::class,
            $configuratorAddToCartCommand, ['validation_groups' => $this->validationGroups]
        );
    }

    /**
     * @param View $view
     *
     * @return Response
     */
    public function restViewHandler(View $view): Response
    {
        $this->restViewHandler->setExclusionStrategyGroups(['sylius', 'Default', 'Details']);
        $this->restViewHandler->setExclusionStrategyVersion('2.0.0');

        $view->getContext()->enableMaxDepth();
        $view->setFormat('json');
        return $this->restViewHandler->handle($view);
    }



    /**
     * @param FormInterface $form
     *
     * @return void
     */
    protected function errorProcessing(FormInterface $form): void {
        /** @var ConfiguratorAddToCartCommandInterface $configuratorAddToCartCommand */
        $configuratorAddToCartCommand = $form->getData();
        $errors                       = $this->getCartItemErrors($configuratorAddToCartCommand->getCartItem());
        if (0 < count($errors)) {
            $this->getAddToCartFormWithErrors($errors, $form);
        }
        $this->orderModifier->addToOrder($configuratorAddToCartCommand->getCart(), $configuratorAddToCartCommand->getCartItem());

        foreach ($form->get('additionalItems') as $childForm) {
            if (empty($childForm->get('cartItem')->get('variant')->getData())) {
               continue;
            }
            /** @var AddToCartCommandInterface $addToCartCommand */
            $addToCartCommand = $childForm->getData();
            $errors           = $this->getCartItemErrors($addToCartCommand->getCartItem());
            if (0 < count($errors)) {
                $this->getAddToCartFormWithErrors($errors, $childForm);
            }
            $this->orderModifier->addToOrder($addToCartCommand->getCart(), $addToCartCommand->getCartItem());
        }
    }


    protected function getCartItemErrors(OrderItemInterface $orderItem): ConstraintViolationListInterface
    {
        return $this->validator
            ->validate($orderItem, null, $this->validationGroups);
    }

    protected function getAddToCartFormWithErrors(ConstraintViolationListInterface $errors, FormInterface $form): FormInterface
    {
        foreach ($errors as $error) {
            $formSelected = empty($error->getPropertyPath())
                ? $form->get('cartItem')
                : $form->get('cartItem')->get($error->getPropertyPath());

            $formSelected->addError(new FormError($error->getMessage()));
        }

        return $form;
    }
}
