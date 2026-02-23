<?php

namespace Oxygen\Core\Preferences;

use Oxygen\Core\Preferences\FieldMetadata;
use Oxygen\Core\Preferences\Loader\LoaderInterface;

class Schema {

    /**
     * The key of the preferences schema.
     *
     * @var string
     */
    protected $key;

    /**
     * The title of the preferences schema.
     *
     * @var string
     */
    protected $title;

    /**
     * The fields the user can edit.
     *
     * @var array
     */
    protected $fields;

    /**
     * The LoaderInterface.
     *
     * @var LoaderInterface
     */
    protected $loader;

    /**
     * Contains preferences values.
     *
     * @var PreferencesStoreInterface
     */
    protected $repository;

    /**
     * Validation rules for the fields
     *
     * @var array
     */
    protected $validationRules;

    /**
     * Constructs the Schema
     *
     * @param string $key
     */
    public function __construct($key) {
        $this->key      = $key;
        $this->title    = $key;
        $this->fields   = [];
        $this->validationRules = [];
    }

    /**
     * Get the key of the preferences schema.
     *
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * Get the title.
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set the title of the preferences schema.
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Loads the Preferences repository.
     *
     * @param LoaderInterface|callable $loader
     * @return void
     */
    public function setLoader($loader) {
        $this->loader = $loader;
    }

    /**
     * Resolves the loader.
     *
     * @return void
     */
    protected function resolveLoader() {
        if(is_callable($this->loader)) {
            $callable = $this->loader;
            $this->loader = $callable();
        }
    }

    /**
     * Loads the Preferences repository.
     *
     * @return void
     */
    protected function loadRepository() {
        $this->resolveLoader();

        $this->repository = $this->loader->load();
    }

    /**
     * Stores the Preferences repository.
     *
     * @return void
     */
    public function storeRepository() {
        $this->resolveLoader();

        if($this->repository != null) {
            $this->loader->store();
        }
    }

    /**
     * Returns the Preferences repository.
     *
     * @return PreferencesStoreInterface
     */
    public function getRepository() {
        if($this->repository === null) {
            $this->loadRepository();
        }

        return $this->repository;
    }

    /**
     * Add a field.
     *
     * @param FieldMetadata $field
     * @return void
     */
    public function addField(FieldMetadata $field) {
        $this->fields[$field->name] = $field;
    }

    /**
     * Constructs a field.
     *
     * @param array $parameters
     * @return void
     */
    public function makeField(array $parameters) {
        $field = new FieldMetadata($parameters['name'], 'text', true);
        unset($parameters['name']);
        foreach($parameters as $key => $value) {
            if($key == 'validationRules') {
                if(is_string($value)) {
                    $value = explode('|', $value);
                }
                $this->validationRules[$field->name] = $value;
                continue;
            }
            $field->$key = $value;
        }
        $this->addField($field);
    }

    /**
     * Adds multiple fields.
     *
     * @param array $fields
     * @return void
     */
    public function makeFields(array $fields) {
        foreach($fields as $item) {
            $this->makeField($item);
        }
    }

    /**
     * Returns a field by its name.
     *
     * @param string $name
     * @return FieldMetadata
     */
    public function getField($name) {
        return $this->fields[$name];
    }

    /**
     * Returns all fields.
     *
     * @return array
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * Returns the combined validation rules for all the fields.
     *
     * @return array
     */
    public function getValidationRules() {
        return $this->validationRules;
    }

}
