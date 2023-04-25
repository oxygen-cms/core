<?php

namespace Oxygen\Core\Controller;

use Illuminate\Http\Request;
use Oxygen\Core\Content\ObjectLinkRegistry;

class ObjectLinkController extends Controller {

    /**
     * given (type, id), return a URL for a link to the given object.
     * @param Request $request
     * @param ObjectLinkRegistry $registry
     * @return \Illuminate\Http\JsonResponse|void
     * @throws \Exception if the link type is unknown
     */
    public function resolveObjectLink(Request $request, ObjectLinkRegistry $registry) {
        $data = $registry->resolve($request->get('type'), intval($request->get('id')));
        return response()->json($data);
    }

}