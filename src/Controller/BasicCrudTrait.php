<?php


namespace Oxygen\Core\Controller;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Lang;
use Oxygen\Core\Http\Notification;
use Oxygen\Data\Behaviour\Searchable;
use Oxygen\Data\Behaviour\Versionable;
use Oxygen\Data\Exception\InvalidEntityException;
use Oxygen\Data\Repository\QueryParameters;
use Oxygen\Data\Repository\SearchMultipleFieldsClause;
use ReflectionClass;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

trait BasicCrudTrait {

    /**
     * List all entities.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \ReflectionException
     */
    public function getListApi(Request $request): JsonResponse {
        $paginator = $this->repository->paginate(self::PER_PAGE, $this->getListQueryParameters($request));

        // render the list
        return response()->json([
            'items' => array_map(function($item) { return $item->toArray(); }, $paginator->items()),
            'totalItems' => $paginator->total(),
            'itemsPerPage' => $paginator->perPage(),
            'status' => Notification::SUCCESS,
        ]);
    }

    /**
     * @param QueryParameters $queryParameters
     * @param Request $request
     * @throws \ReflectionException
     */
    protected function maybeAddSearchClause(QueryParameters $queryParameters, Request $request = null) {
        if($request === null) {
            $request = app('request');
        }
        $searchQuery = $request->input('q', null);
        if($searchQuery !== null) {
            $class = new ReflectionClass($this->repository->getEntityName());
            if($class->implementsInterface(Searchable::class)) {
                $searchableFields = $class->getMethod('getSearchableFields')->invoke(null);
                $queryParameters->addClause(new SearchMultipleFieldsClause($searchableFields, $searchQuery));
            }
        }
    }

    /**
     * Returns filters for the 'list' operation
     *
     * @param Request $request
     * @return QueryParameters
     * @throws \ReflectionException
     */
    protected function getListQueryParameters(Request $request) {
        $queryParameters = QueryParameters::make()
            ->orderBy('id', QueryParameters::DESCENDING);

        $this->maybeAddSearchClause($queryParameters, $request);

        return $queryParameters;
    }

    /**
     * Shows info about an entity.
     *
     * @param mixed $item the item
     * @return JsonResponse
     */
    public function getInfoApi($item) {
        $item = $this->repository->find((int) $item);

        return response()->json([
            'status' => Notification::SUCCESS,
            'item' => $item->toArray()
        ]);
    }

    /**
     * Creates a new Resource - returns JSON response.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function postCreateApi(Request $request) {
        $item = $this->repository->make();
        $item->fromArray($request->except(['_token']));
        $this->repository->persist($item);

        return response()->json([
            'status' => Notification::SUCCESS,
            'content' => trans('oxygen/core::messages.basic.created'),
            'item' => $item->toArray()
        ]);
    }

    /**
     * Updates a Resource - returns a JSON response.
     *
     * @param Request $request
     * @param mixed $item the item
     * @return JsonResponse
     * @throws InvalidEntityException
     */
    public function putUpdateApi(Request $request, $item) {
        $item = $this->repository->find((int) $item);
        $userInput = $request->except(['_token', 'version', 'updateStage', 'autoConvertToDraft']);
        $createNewVersion = $request->input('version', Versionable::GUESS_IF_NEW_VERSION_REQUIRED);
        if($createNewVersion === 'false')
        {
            $createNewVersion = false;
        }

        if($item instanceof \Oxygen\Data\Behaviour\Publishable && $request->input('autoConvertToDraft', 'yes') !== 'no')
        {
            $stage = (int) $request->input('stage', $item->getStage());
            if($item->isPublished() && $stage != \Oxygen\Data\Behaviour\Publishable::STAGE_DRAFT)
            {
                logger()->info(auth()->user()->getAuthIdentifier() . ' updated ' . get_class($item) . ' id=' . $item->getId() . ' - making draft version');
                $this->repository->makeDraftOfVersion($item, false);
                $item->setStage(\Oxygen\Data\Behaviour\Publishable::STAGE_DRAFT);
                $createNewVersion = Versionable::NO_NEW_VERSION;
                unset($userInput['stage']);
            }
        }

        $item->fromArray($userInput);
        if($item instanceof Versionable)
        {
            $this->repository->persist($item, true, $createNewVersion);
        } else {
            $this->repository->persist($item, true);
        }

        return response()->json([
            'content' => trans('oxygen/core::messages.basic.updated'),
            'status' => Notification::SUCCESS,
            'item' => $item->toArray()
        ]);
    }

    /**
     * Deletes a Resource.
     *
     * @param mixed $item the item
     * @return JsonResponse
     */
    public function deleteDeleteApi($item) {
        $item = $this->repository->find((int) $item);
        $this->repository->delete($item);

        return response()->json([
            'content' => trans('oxygen/core::messages.basic.deleted'),
            'status' => Notification::SUCCESS
        ]);
    }

    public static function registerCrudRoutes(Router $router) {
        $resourceName = explode('/', $router->getLastGroupPrefix());
        $resourceName = Str::camel(last($resourceName));
        $router->get('/', static::class . '@getListApi')
            ->name("$resourceName.getListApi")
            ->middleware("oxygen.permissions:$resourceName.getList");
        $router->post('/', static::class . '@postCreateApi')
            ->name("$resourceName.postCreateApi")
            ->middleware("oxygen.permissions:$resourceName.postCreate");
        $router->put('/{id}', static::class . '@putUpdateApi')
            ->name("$resourceName.putUpdateApi")
            ->middleware("oxygen.permissions:$resourceName.putUpdate");
        $router->delete('/{id}', static::class . '@deleteDeleteApi')
            ->name("$resourceName.deleteDeleteApi")
            ->middleware("oxygen.permissions:$resourceName.deleteDelete");
        $router->get("/{id}", static::class . '@getInfoApi')
            ->name("$resourceName.getInfoApi")
            ->middleware("oxygen.permissions:$resourceName.getInfo");
    }

    public static function setupLangMappings(array $mappings) {
        Lang::when('oxygen/core::messages', $mappings);
        Lang::when('oxygen/core::dialogs', $mappings);
        Lang::when('oxygen/core::ui', $mappings);
    }
}
