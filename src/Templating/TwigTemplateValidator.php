<?php


namespace Oxygen\Core\Templating;

use Oxygen\Core\View\Factory;
use ReflectionException;

class TwigTemplateValidator {
    /**
     * The view factory compiles and renders the view, throwing an exception if there is an error in it.
     *
     * @var TwigTemplateCompiler
     */
    protected $compiler;

    /**
     * Stores the last exception that was thrown while validating.
     * Because the code within Laravel's Validator class runs synchronously,
     * this should always be corresponding to the last field that was `validate`d
     *
     * @var \Exception
     */
    protected $lastExceptionThrown;

    /**
     * Constructs the validator
     *
     * @param TwigTemplateCompiler $compiler
     */
    public function __construct(TwigTemplateCompiler $compiler) {
        $this->compiler = $compiler;
    }

    /**
     * Validates the code, by compiling it, and executing it,
     * and ensuring it doesn't throw any exceptions.
     *
     * @param string $attribute
     * @param string $value
     * @param array $parameters
     * @param \Illuminate\Validation\Validator $validator
     * @return bool
     * @throws \Throwable
     */
    public function validate($attribute, $value, $parameters, $validator) {
        try {
            $this->compiler->renderString($value, $attribute);
            return true;
        } catch(\Exception $e) {
            $this->lastExceptionThrown = $e;
            return false;
        }
    }

    /**
     * Replaces the bland validation message with more important information about
     * the exception that occured.
     *
     * @param string $message the original message
     * @param string $attribute
     * @param string $rule
     * @param array $parameters
     * @return string the new message
     * @throws ReflectionException
     */
    public function replace($message, $attribute, $rule, $parameters) {
        // trim off the (View: xxx) cruft wrapping the original exception
        $e = $this->lastExceptionThrown;
        // make replacements
        $message = str_replace(':exception.message', $e->getMessage(), $message);
        $message = str_replace(':exception.line', $e->getLine(), $message);
        $message = str_replace(':exception.file', $e->getFile(), $message);
        $reflect = new \ReflectionClass($e);
        $message = str_replace(':exception.shortClassName', $reflect->getShortName(), $message);
        $message = str_replace(':exception.className', $reflect->getName(), $message);
        return $message;
    }

}