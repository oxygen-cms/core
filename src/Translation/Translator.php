<?php

namespace Oxygen\Core\Translation;

use Illuminate\Contracts\Translation\Loader;
use Illuminate\Translation\Translator as BaseTranslator;
use Illuminate\Support\Str;

class Translator extends BaseTranslator {

    /**
     * Bulk replacements to be applied to translations.
     *
     * @var array
     */

    protected $bulkReplacements;

    /**
     * Create a new translator instance.
     *
     * @param  \Illuminate\Contracts\Translation\Loader  $loader
     * @param  string  $locale
     * @return void
     */
    public function __construct(Loader $loader, $locale) {
        parent::__construct($loader, $locale);
        $this->bulkReplacements = [];
    }

    /**
     * The given replacements will be used whenever a translation beginning with $key is retrieved.
     *
     * @param string $key
     * @param array  $replace
     * @return void
     */
    public function when($key, array $replace) {
        $this->bulkReplacements[$key] = $replace;
    }

    /**
     * Get the translation for the given key.
     *
     * @param  string  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @param  bool  $fallback
     * @return string|array
     */
    public function get($key, array $replace = [], $locale = null, $fallback = true) {
        foreach($this->bulkReplacements as $match => $extraReplace) {
            if(Str::startsWith($key, $match)) {
                $replace = array_merge($replace, $extraReplace);
            }
        }

        return parent::get($key, $replace, $locale, $fallback);
    }

}
