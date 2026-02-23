<?php

namespace Oxygen\Core\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Router;
use Illuminate\Support\Str;
use Oxygen\Core\Http\Notification;
use Oxygen\Data\Exception\InvalidEntityException;

trait PublishableCrudTrait {

    /**
     * Publish or unpublish an entity.
     *
     * @param mixed $item the item
     * @return JsonResponse
     * @throws InvalidEntityException
     */
    public function publish($item): JsonResponse {
        $item = $this->getItem($item);
        $item->publish();
        $this->repository->persist($item, true, 'overwrite');

        return response()->json(['item' => $item->toArray(), 'status' => Notification::SUCCESS, 'content' => 'Successfully published']);
    }

    /**
     * Registers API routes.
     *
     * @param Router $router
     */
    public static function registerPublishableRoutes(Router $router) {
        $resourceName = explode('/', $router->getLastGroupPrefix());
        $resourceName = Str::camel(last($resourceName));
        $router->post("/{id}/publish", static::class . "@publish")
            ->name("$resourceName.postPublishApi")
            ->middleware("oxygen.permissions:$resourceName.postPublish");
    }

}
