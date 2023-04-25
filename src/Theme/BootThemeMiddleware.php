<?php

namespace Oxygen\Core\Theme;

use Closure;

class BootThemeMiddleware {

    /**
     * The theme manager.
     *
     * @var ThemeManager
     */
    protected $themeManager;

    /**
     * Create a new filter instance.
     *
     * @param  ThemeManager  $themes
     */
    public function __construct(ThemeManager $themes) {
        $this->themeManager = $themes;
    }

    /**
     * Handle an incoming request.
     *
     * @param  mixed  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        try {
            $this->themeManager->current()->boot();
        } catch(ThemeNotFoundException $e) {
            // no theme to boot
        }

        return $next($request);
    }
}
