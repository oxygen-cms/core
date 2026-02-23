<?php


namespace Oxygen\Core\Preferences;

use Closure;
use Generator;
use Illuminate\Support\Arr;

class ChainedStore implements PreferencesStoreInterface, FallbackStoreInterface {

    /**
     * A function which returns an iterator over preferences.
     * Preferences will be searched
     *
     * @var Closure
     */
    protected $preferencesGenerator;

    protected PreferencesSettingInterface $setter;

    /**
     * Constructs a Chained preferences store
     *
     * @param Closure $preferencesGenerator
     * @param PreferencesSettingInterface $setter
     */
    public function __construct(Closure $preferencesGenerator, PreferencesSettingInterface $setter) {
        $this->preferencesGenerator = $preferencesGenerator;
        $this->setter = $setter;
    }

    /**
     * Get the specified preferences item.
     *
     * @param string $key key using dot notation
     * @return mixed
     * @throws PreferenceNotFoundException
     */
    public function get(string $key) {
        $generator = $this->getGenerator();
        foreach($generator as $preferences) {
            $value = Arr::get($preferences, $key);
            if($value !== null) {
                return $value;
            }
        }
        throw new PreferenceNotFoundException('Preference not found with key ' . $key);
    }

    /**
     * Gets the value for the given key, in any is given.
     *
     * @param string $key key using dot notation
     * @return mixed|null the fallback value, or null if not found
     */
    public function getFallback(string $key) {
        $generator = $this->getGenerator();
        $skip = true;
        foreach($generator as $preferences) {
            if($skip) {
                $skip = false;
                continue;
            }
            $value = Arr::get($preferences, $key);
            if($value !== null) {
                return $value;
            }
        }
        return null;
    }


    /**
     * Gets the value for the given key, in any is given. Otherwise throws.
     *
     * @param string $key key using dot notation
     * @return mixed|null the fallback value, or null if not found
     */
    function getPrimary(string $key) {
        $generator = $this->getGenerator();
        $primaryPreferences = $generator->current();
        return Arr::get($primaryPreferences, $key);
    }

    /**
     * Sets the specified preferences item.
     * Will not persist changes automatically.
     *
     * @param string $key key using dot notation
     * @param mixed $value new value
     * @return void
     */
    public function set(string $key, $value) {
        $this->setter->set($key, $value);
    }

    /**
     * Checks if the preferences item has been set.
     *
     * @param string $key key using dot notation
     * @return boolean
     */
    public function has(string $key): bool {
        try {
            $this->get($key);
            return true;
        } catch(PreferenceNotFoundException $e) {
            return false;
        }
    }

    /**
     * Get the specified preferences item.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getOrDefault(string $key, $default) {
        try {
            return $this->get($key);
        } catch(PreferenceNotFoundException $e) {
            return $default;
        }
    }

    public function getGenerator(): Generator {
        return ($this->preferencesGenerator)();
    }
}
