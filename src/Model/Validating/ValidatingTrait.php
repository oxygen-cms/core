<?php

namespace Oxygen\Core\Model\Validating;

use Validator;

use Watson\Validating\ValidatingTrait as BaseValidatingTrait;

trait ValidatingTrait {

    use BaseValidatingTrait;

    /**
     * Validation Rules for the Model
     *
     * @var array
     */

    protected $rules = [];

    /**
     * Overrides the Base trait's bootValidatingTrait.
     * An invalid model will now throw an exception.
     */

    public static function bootValidatingTrait() {
        static::saving(function($model) {
            $result = $model->isValid();
            if(!$result) {
                throw new InvalidModelException($model);
                return false;
            }
        });
    }

    /**
     * Rule macros that will be run through str_replace
     *
     * @var array
     */

    public $simpleRuleMacros = [
        'search' => [
            'unique_ignore_self',
            'unique_ignore_versions'
        ],
        'replace' => [
            'unique:{{ table }},{{ field }},{{ id }}',
            'unique:{{ table }},{{ field }},{{ getHeadKey() }},{{ getKeyName() }},{{ getVersionHeadColumn() }},!=,{{ getHeadKey() }}'
        ]
    ];

    /**
     * Rule macros that will be run through preg_replace
     *
     * @var array
     */

    public $regexRuleMacros = [
        'search' => [
            '/[{]+[ ]*table[ ]*[}]+/',
            '/[{]+[ ]*id[ ]*[}]+/'
        ],
        'replace' => [
            '{{ getTable() }}',
            '{{ getKey() }}'
        ]
    ];

    /**
     * Make a Validator instance for a given ruleset.
     *
     * @param  array $rules
     * @return \Illuminate\Validation\Factory
     */

    protected function validate($rules = []) {
        $rules = $this->exists ? $this->getRulesWithUniqueIdentifiers() : $this->getRules();
        $messages = $this->getMessages();

        $validation = Validator::make($this->toArray(), $rules, $messages);
        $this->customValidator($validation);

        if ($validation->passes()) return true;

        $this->errors = $validation->messages();

        return false;
    }

    /**
     * Customises the validator instance
     *
     * @param \Illuminate\Validation\Factory $validator
     * @return \Illuminate\Validation\Factory
     */

    protected function customValidator($validator) {

    }

    /**
     * Process validation rules and modify them if needed.
     *
     * @param array $rules
     * @return array rules
     */

    protected function processRules(array $rules) {
        foreach($rules as $name => &$field) {
            if(is_array($field)) {
                foreach($field as &$rule) {
                    $rule = $this->processRule($name, $rule);
                }
            }
        }

        return $rules;
    }

    /**
     * Compiles a validation rule using a custom expression syntax.
     *
     * @param string $rule
     * @return string
     */

    public function processRule($field, $rule) {
        // simple replacements
        $rule = str_replace($this->simpleRuleMacros['search'], $this->simpleRuleMacros['replace'], $rule);

        // regex replacements
        $rule = preg_replace($this->regexRuleMacros['search'], $this->regexRuleMacros['replace'], $rule);

        // dynamic replacements
        $rule = preg_replace('/[{]+[ ]*field[ ]*[}]+/', $field, $rule);

        // remove the id parameter if it doesn't exist
        if(!$this->exists) {
            $rule = preg_replace(['/[{]+[ ]*getKey()[ ]*[}]+/'], ['NULL'], $rule);
        }

        // attributes
        if(preg_match_all('/[{]+[ ]*([\w]+)[ ]*[}]+/', $rule, $matches)) {
            for($i = 0; $i < count($matches[0]); $i++) {
                $attribute = $matches[1][$i];
                $rule = str_replace($matches[0][$i], $this->{$attribute}, $rule);
            }
        }

        // methods
        if(preg_match_all('/[{]+[ ]*([\w]+)\(\)[ ]*[}]+/', $rule, $matches)) {
            for($i = 0; $i < count($matches[0]); $i++) {
                $method = $matches[1][$i];
                $rule = str_replace($matches[0][$i], $this->{$method}(), $rule);
            }
        }

        return $rule;
    }

}