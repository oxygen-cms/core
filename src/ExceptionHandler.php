<?php

namespace Oxygen\Core;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\Session\TokenMismatchException;
use Oxygen\Data\Exception\InvalidEntityException;
use Oxygen\Preferences\PreferencesManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionHandler extends Handler {

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        TokenMismatchException::class,
        AuthorizationException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        InvalidEntityException::class
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Convert an authentication exception into a response.
     * We customize this response to add 'code' => 'unauthenticated'
     *
     * @param Request  $request
     * @param AuthenticationException $exception
     * @return Response
     */
    protected function unauthenticated($request, AuthenticationException $exception) {
        // our SPA uses the `location` query parameter to decide where to send the user to next...
        $targetURL = Redirect::intended()->getTargetUrl();

        return $request->expectsJson()
            ? response()->json(['message' => $exception->getMessage(), 'code' => 'unauthenticated'], 401)
            : redirect()->guest($exception->redirectTo() ?? (route('login') . '?location=' . urlencode($targetURL)));
    }

    /**
     * Register the error template hint paths.
     *
     * We override the original behaviour to allow themes to provide their
     * own error pages which will override the default error pages.
     *
     * @return void
     */
    protected function registerErrorViewPaths() {
        $paths = collect(config('view.paths'));
        $fallbackPaths = $paths->map(function ($path) {
            return "{$path}/errors";
        })->push(base_path('vendor/laravel/framework/src/Illuminate/Foundation/Exceptions/views'))->all();

        $errorViewPrefix = app(PreferencesManager::class)->get('appearance.site::errorViewPrefix', '');

        $themePaths = [];
        if($errorViewPrefix !== '') {
            try {
                $errorViewPrefixParts = explode('::', $errorViewPrefix);
                $hints = app('view')->getFinder()->getHints();
                foreach($hints[$errorViewPrefixParts[0]] as $pathRoot) {
                    $pathRoot = realpath($pathRoot);
                    $extraErrorPath = realpath($pathRoot . '/' . str_replace('.', '/', $errorViewPrefixParts[1]));
                    if(strpos($extraErrorPath, $pathRoot) === 0) {
                        $themePaths[] = $extraErrorPath;
                    }
                }
            } catch(Exception $e) {
                // don't bother
            }
        }

        $paths = array_merge($themePaths, $fallbackPaths);

        app('view')->replaceNamespace('errors', $paths);
    }

}
