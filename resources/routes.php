<?php

use Illuminate\Routing\Router;
use Oxygen\Core\Controller\ObjectLinkController;
use Oxygen\Core\Controller\PreferencesController;

$router = app('router');

$router->prefix('/oxygen/api')
    ->middleware('api_auth')
    ->group(function(Router $router) {

        // object links
        $router->prefix('object-link')->group(function(Router $router) {
            $router->get('resolve', [ObjectLinkController::class, 'resolveObjectLink'])->middleware("oxygen.permissions:objectLink.resolve");;
        });

        // preferences
        $router->get('preferences/{key}', [PreferencesController::class, 'getValue'])
            ->name("preferences.getValue")
            ->middleware("oxygen.permissions:preferences.getValue");

        $router->post('preferences/{key}/validate', [PreferencesController::class, 'postCheckIsValueValid'])
            ->name("preferences.postCheckIsValueValid")
            ->middleware("oxygen.permissions:preferences.putUpdate");

        $router->put('preferences/{key}', [PreferencesController::class, 'putUpdateValue'])
            ->name("preferences.putUpdateValue")
            ->middleware("oxygen.permissions:preferences.putUpdate");

    });
