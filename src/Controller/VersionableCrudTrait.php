<?php


namespace Oxygen\Core\Controller;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Str;
use Oxygen\Core\Http\Notification;
use Oxygen\Data\Repository\QueryParameters;

trait VersionableCrudTrait {

    /**
     * Filters out past versions and trashed items.
     *
     * @param Request $request
     * @return QueryParameters
     * @throws \ReflectionException
     */
    protected function getListQueryParameters(Request $request): QueryParameters {
        $queryParameters = QueryParameters::make();
        if($request->get('trash') == 'true') {
            $queryParameters = $queryParameters->onlyTrashed();
        } else {
            $queryParameters = $queryParameters->excludeTrashed();
        }

        $queryParameters = $queryParameters
            ->excludeVersions()
            ->orderBy('id', QueryParameters::DESCENDING);

        $this->maybeAddSearchClause($queryParameters, $request);

        if($request->has('sortField') && in_array($request->get('sortField'), static::ALLOWED_SORT_FIELDS) &&
            in_array($request->get('sortOrder'), ['asc', 'desc'])) {
            $queryParameters = $queryParameters->orderBy($request->get('sortField'), strtoupper($request->get('sortOrder')));
        }

        return $queryParameters;
    }

    /**
     * Lists all versions of an entity.
     *
     * @param mixed $item the item
     * @return JsonResponse
     */
    public function listVersionsApi($item) {
        $item = $this->repository->find($item);
        $versions = $item->getVersions();

        return response()->json([
            'items' => array_merge([$item->getHead()->toArray()], $versions->map(function($item) { return $item->toArray(); })->toArray()),
            'status' => Notification::SUCCESS
        ]);
    }

    /**
     * Makes a new version of an entity.
     *
     * @param mixed $item the item
     * @return JsonResponse
     */
    public function postNewVersion($item) {
        $item = $this->repository->find($item);
        $this->repository->makeNewVersion($item);

        return response()->json([
            'content' => __('oxygen/core::messages.versionable.madeVersion'),
            'status' => Notification::SUCCESS
        ]);
    }

    /**
     * Makes the version the head version.
     *
     * @param mixed $item the item
     * @return JsonResponse
     */
    public function postMakeHeadVersion($item) {
        $item = $this->repository->find($item);

        if($item->isHead()) {
            return response()->json([
                'content' => __('oxygen/core::messages.versionable.alreadyHead'),
                'status' => Notification::FAILED
            ]);
        }

        $this->repository->makeHeadVersion($item);

        return response()->json([
            'content' => __('oxygen/core::messages.versionable.madeHead'),
            'status' => Notification::SUCCESS
        ]);
    }

    /**
     * Clears all older versions of the item.
     *
     * @param mixed $item the item
     * @return JsonResponse
     */
    public function deleteVersions($item) {
        $item = $this->repository->find($item);
        $this->repository->clearVersions($item);

        return response()->json([
            'content' => __('oxygen/core::messages.versionable.clearedVersions'),
            'status' => Notification::SUCCESS
        ]);
    }

    /**
     * Registers API routes.
     *
     * @param Router $router
     * @param string $resourceName
     */
    public static function registerVersionableRoutes(Router $router) {
        $resourceName = explode('/', $router->getLastGroupPrefix());
        $resourceName = Str::camel(last($resourceName));
        $router->get("/{id}/versions", static::class . "@listVersionsApi")
            ->name("$resourceName.listVersionsApi")
            ->middleware("oxygen.permissions:$resourceName.listVersions");
        $router->post("/{id}/make-head", static::class . "@postMakeHeadVersion")
            ->name("$resourceName.postMakeHeadVersionApi")
            ->middleware("oxygen.permissions:$resourceName.postMakeHeadVersion");
    }
}
