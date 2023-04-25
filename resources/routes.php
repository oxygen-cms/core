<?php

use Illuminate\Routing\Router;
use Oxygen\Core\Controller\ObjectLinkController;

$router = app('router');

$router->prefix('/oxygen/api')
    ->middleware('api_auth')
    ->group(function(Router $router) {

        // object links
        $router->prefix('object-link')->group(function(Router $router) {
            $router->get('resolve', [ObjectLinkController::class, 'resolveObjectLink'])->middleware("oxygen.permissions:objectLink.resolve");;
        });

    });
