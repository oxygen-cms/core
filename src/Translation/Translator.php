<?php

namespace Oxygen\Core\Translation;

use Illuminate\Translation\Translator as BaseTranslator;
use Illuminate\Translation\LoaderInterface;

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
     * @param  LoaderInterface $loader
     * @param  string          $locale
     */
    public function __construct(LoaderInterface $loader, $locale) {
        parent::__construct($loader, $locale);
        $this->bulkReplacements = [];
    }

    /**
     * The given replacements will be used whenever a translation beginning with $key is retrieved.
     *
     * @param string $key
     * @param array $replace
     * @return void
     */
    public function when($key, array $replace) {
        $this->bulkReplacements[$key] = $replace;
    }

    /**
     * Get the translation for the given key.
     *
     * @param  string  $key
     * @param  array   $replace
     * @param  string  $locale
     * @return string
     */
    public function get($key, array $replace = [], $locale = null) {
        foreach($this->bulkReplacements as $match => $extraReplace) {
            if(starts_with($key, $match)) {
                $replace = array_merge($replace, $extraReplace);
            }
        }

        return parent::get($key, $replace, $locale);
    }

}
