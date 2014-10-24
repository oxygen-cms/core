<?php

namespace Oxygen\Core\Validation;

use Seld\JsonLint\JsonParser;
use Seld\JsonLint\ParsingException;

use Illuminate\Validation\Validator as BaseValidator;
use Illuminate\Hashing\HasherInterface;
use Illuminate\View\Factory;
use Illuminate\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;

class Validator extends BaseValidator {

    /**
     * The Hasher implementation.
     *
     * @var \Illuminate\Hashing\HasherInterface
     */

    protected $hasher;

    /**
     * The View factory.
     *
     * @var \Illuminate\View\Factory
     */

    protected $view;

    /**
     * The Router instance.
     *
     * @var \Illuminate\Routing\Router
     */

    protected $router;

    /**
     * Create a new Validator instance.
     *
     * @param  \Symfony\Component\Translation\TranslatorInterface  $translator
     * @param  HasherInterface  $hasher
     * @param  Factory          $view
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return void
     */
    public function __construct(TranslatorInterface $translator, HasherInterface $hasher, Factory $view, Router $router, array $data, array $rules, array $messages = array(), array $customAttributes = array()) {
        parent::__construct($translator, $data, $rules, $messages, $customAttributes);
        $this->hasher = $hasher;
        $this->view = $view;
        $this->router = $router;
    }

    /**
     * Check the value is a URL slug.
     *
     * foo-for-the-win/i-am-a-horse-123
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */

    public function validateSlug($attribute, $value, $parameters) {
        return preg_match('#^[a-z0-9/\-]+$#', $value);
    }

    /**
     * Check the value is a URL slug.
     *
     * foo-for-the-win/i-am-a-horse-123.jpg
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */

    public function validateSlugExtended($attribute, $value, $parameters) {
        return preg_match('#^[a-z0-9/\-.]+$#', $value);
    }

    /**
     * Check the value is a URL slug without any '/'
     *
     * i-am-a-horse-123.jpg
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */

    public function validateSlugExtendedNoDir($attribute, $value, $parameters) {
        return preg_match('#^[a-z0-9\-.]+$#', $value);
    }

    /**
     * Check the value consists of just alphanumeric characters and dots.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */

    public function validateAlphaDot($attribue, $value, $parameters) {
        return preg_match('/^[\pL\pM\pN\.]+$/u', $value);
    }

    /**
     * Check the value is a name.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */

    public function validateName($attribue, $value, $parameters) {
        return preg_match('/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð& ,.\'-]+$/u', $value);
    }

    /**
     * Check if the value is valid JSON.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */

    public function validateJson($attribute, $value, $parameters) {
        $parser = new JsonParser();
        $exception = $parser->lint($value);

        if($exception instanceof ParsingException) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Check the field is the given value.
     *
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */

    public function validateHashesTo($attribute, $value, $parameters) {
        $this->requireParameterCount(1, $parameters, 'hashes_to');

        return $this->hasher->check($value, $parameters[0]);
    }

    /**
     * Determines if the given view exists.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */

    public function validateViewExists($attribute, $value, $parameters) {
        return $this->view->exists($value);
    }

    /**
     * Replace all place-holders for the in rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */

    public function replaceViewExists($message, $attribute, $rule, $parameters) {
        return str_replace(':view', $this->getValue($attribute), $message);
    }

    /**
     * Determines if the given route exists.
     * If a parameter is provided it will determine whether the routes are fetched by name or action.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */

    public function validateRouteExists($attribute, $value, $parameters) {
        $method = 'getBy' . studly_case(isset($parameters[0]) ? $parameters[0] : 'name');
        $routes = $this->router->getRoutes();
        return $routes->$method($value) !== null;
    }

    /**
     * Replace all place-holders for the in rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */

    public function replaceRouteExists($message, $attribute, $rule, $parameters) {
        return str_replace(':route', $this->getValue($attribute), $message);
    }

    /**
     * Get the extra conditions for a unique / exists rule.
     *
     * @param  array  $segments
     * @return array
     */

    protected function getExtraConditions(array $segments) {
        $extra = array();

        $count = count($segments);

        $operators = [
            '=', '<', '>', '<=', '>=', '<>', '!='
        ];

        // if any of the operators are in the segments
        if(!!array_intersect($operators, $segments)) {
            for ($i = 0; $i < $count; $i = $i + 3) {
                $extra[$segments[$i]] = [$segments[$i + 1], $segments[$i + 2]];
            }
        } else {
            for ($i = 0; $i < $count; $i = $i + 2) {
                $extra[$segments[$i]] = $segments[$i + 1];
            }
        }

        return $extra;
    }

}