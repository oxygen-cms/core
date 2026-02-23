<?php


namespace Oxygen\Core\Controller;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Router;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Oxygen\Core\Http\Notification;
use Oxygen\Data\Exception\InvalidEntityException;
use Oxygen\Data\Repository\QueryParameters;

trait SoftDeleteCrudTrait {

    /**
     * Filters out past versions and trashed items.
     *
     * @param Request $request
     * @return QueryParameters
     */
    protected function getListQueryParameters(Request $request) {
        $queryParameters = QueryParameters::make();
        if($request->get('trash') == 'true') {
            $queryParameters = $queryParameters->onlyTrashed();
        } else {
            $queryParameters = $queryParameters->excludeTrashed();
        }

        $queryParameters = $queryParameters
            ->orderBy('id', QueryParameters::DESCENDING);

        $this->maybeAddSearchClause($queryParameters, $request);

        return $queryParameters;
    }

    /**
     * Deletes an entity.
     *
     * @param mixed $item the item
     * @return JsonResponse
     */
    public function deleteDeleteApi(Request $request, $item) {
        $item = $this->repository->find((int) $item);
        if($request->has('force')) {
            $this->repository->delete($item);
            return response()->json([
                'content' => __('oxygen/core::messages.softDelete.forceDeleted'),
                'status' => Notification::SUCCESS
            ]);
        } else {
            $item->delete();
            $this->repository->persist($item);

            return response()->json([
                'content' => __('oxygen/core::messages.basic.deleted'),
                'status' => Notification::SUCCESS,
                'item' => $item->toArray()
            ]);
        }
    }

    /**
     * Restores a deleted entity.
     *
     * @param mixed $item the item
     * @return JsonResponse
     */
    public function postRestoreApi($item) {
        $item = $this->repository->find((int) $item);
        $item->restore();
        $this->repository->persist($item);

        return response()->json([
            'content' => __('oxygen/core::messages.softDelete.restored'),
            'status' => Notification::SUCCESS,
            'item' => $item->toArray()
        ]);
    }

    public static function registerSoftDeleteRoutes(Router $router) {
        $resourceName = explode('/', $router->getLastGroupPrefix());
        $resourceName = Str::camel(last($resourceName));
        $router->post('/{id}/restore', static::class . '@postRestoreApi')
            ->name($resourceName . '.postRestoreApi')
            ->middleware("oxygen.permissions:$resourceName.postRestore");
    }

}
