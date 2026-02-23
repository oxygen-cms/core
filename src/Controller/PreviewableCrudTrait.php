<?php

namespace Oxygen\Core\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Oxygen\Core\Templating\TwigTemplateCompiler;
use Twig\Error\Error;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * The Previewable trait extends a Versionable resource,
 * and adds a page that shows some 'content' of the resource rendered as HTML.
 */
trait PreviewableCrudTrait {

    /**
     * Registers routes for previewable content.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public static function registerPreviewableRoutes($router) {
        $resourceName = explode('/', $router->getLastGroupPrefix());
        $resourceName = \Illuminate\Support\Str::camel(last($resourceName));
        $router->post('content/{id}', [static::class, 'postContent'])
            ->name("$resourceName.postContentApi");
    }

    /**
     * Renders custom content as HTML.
     *
     * @param TwigTemplateCompiler $templating
     * @param Request $request
     * @param null $item
     * @return Response|View
     */
    public function postContent(TwigTemplateCompiler $templating, Request $request, $item = null) {
        try {
            $content = $request->get('content', null);
            if(!$content && !$item) {
                $content = '';
            }

            $renderLayout = $request->get('renderLayout', true);
            // Handle both boolean and string values
            if (is_string($renderLayout)) {
                $renderLayout = $renderLayout === 'true';
            }

            return $this->getContent($templating, $content, $renderLayout, $item);
        } catch(Error $e) {
            return response($e->getMessage());
        }
    }

    /**
     * Renders this resource as HTML
     *
     * @param TwigTemplateCompiler $templating
     * @param string|null $contentOverride
     * @param bool $renderLayout
     * @param object|null $item
     * @return View
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function getContent(TwigTemplateCompiler $templating, ?string $contentOverride, bool $renderLayout, $item) {
        if($item !== null) {
            $item = $this->getItem($item);
        }

        $rendered = $this->renderEntityContent($templating, $contentOverride, $item);

        if(method_exists($this, 'decorateContent') && $renderLayout) {
            return $this->decorateContent($rendered, $item);
        } else {
            return $this->decoratePreviewContent($rendered, $item);
        }
    }

    /**
     * @param TwigTemplateCompiler $templating
     * @param string|null $contentOverride
     * @param $item
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function renderEntityContent(TwigTemplateCompiler $templating, ?string $contentOverride, $item) {
        if($contentOverride !== null) {
            return $templating->renderString($contentOverride, $item);
        } else {
            return $templating->render($item);
        }
    }
}
