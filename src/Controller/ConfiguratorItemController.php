<?php
declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Controller;

use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ConfiguratorItemController
 * @package Asdoria\SyliusConfiguratorPlugin\Controller
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfiguratorItemController extends ResourceController
{
    /**
     * @throws HttpException
     */
    public function updatePositionsAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::UPDATE);
        $configuratorItemsToUpdate = $request->get('configuratorItems');

        if ($configuration->isCsrfProtectionEnabled() && !$this->isCsrfTokenValid('update-configurator-item-position', $request->request->get('_csrf_token'))) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Invalid csrf token.');
        }

        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'], true) && null !== $configuratorItemsToUpdate) {
            foreach ($configuratorItemsToUpdate as $configuratorItemToUpdate) {
                if (!is_numeric($configuratorItemToUpdate['position'])) {
                    throw new HttpException(
                        Response::HTTP_NOT_ACCEPTABLE,
                        sprintf('The configuratorItem position "%s" is invalid.', $configuratorItemToUpdate['position'])
                    );
                }

                /** @var ConfiguratorItemInterface $configuratorItem */
                $configuratorItem = $this->repository->findOneBy(['id' => $configuratorItemToUpdate['id']]);
                $configuratorItem->setPosition((int) $configuratorItemToUpdate['position']);
                $this->manager->flush();
            }
        }

        return new JsonResponse();
    }
}
