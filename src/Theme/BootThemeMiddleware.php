<?php


namespace Oxygen\Core\Theme;

use Closure;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
    public function __construct(ThemeManager $themes)
    {
        $this->themeManager = $themes;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $currentTheme = $this->themeManager->current();
        if($currentTheme) {
            $currentTheme->boot();
        }

        return $next($request);
    }
}
