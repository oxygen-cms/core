<?php


namespace Oxygen\Core\Preferences;


use Illuminate\Support\Arr;
use Oxygen\Core\Theme\Theme;
use Oxygen\Core\Theme\ThemeManager;
use Oxygen\Core\Theme\ThemeNotFoundException;

/**
 * Allow the use of theme-wide preferences as a fallback, when the required preference item is not set.
 */
class ThemeSpecificPreferencesFallback implements PreferencesStorageInterface {

    /**
     * @var ThemeManager
     */
    private ThemeManager $themeManager;

    /**
     * @var Theme|null
     */
    private ?Theme $currentTheme = null;

    /**
     * @var string
     */
    private $key;

    public function __construct(ThemeManager $themeManager, string $key) {
        $this->themeManager = $themeManager;
        $this->key = $key;
    }

    /**
     * @return array
     */
    public function getPreferences(): array {
        $currentTheme = $this->getCurrentTheme();
        if($currentTheme === null || !isset($currentTheme->getProvides()[$this->key])) { return []; }
        return $currentTheme->getProvides()[$this->key];
    }

    /**
     * @throws \Exception
     */
    public function setPreferences(array $preferences) {
        throw new \Exception('ThemeSpecificPreferencesFallback is readonly');
    }

    private function getCurrentTheme(): ?Theme {
        if($this->currentTheme === null) {
            try {
                $this->currentTheme = $this->themeManager->current();
            } catch (ThemeNotFoundException $e) {
                // do nothing
            }
        }
        return $this->currentTheme;
    }
}
