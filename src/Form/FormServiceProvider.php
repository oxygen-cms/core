<?php

namespace Oxygen\Core\Form;

use Illuminate\Support\ServiceProvider;
use Oxygen\Core\Form\Type\BaseType;
use Oxygen\Core\Form\Type\BooleanType;
use Oxygen\Core\Form\Type\DatetimeType;
use Oxygen\Core\Form\Type\DateType;
use Oxygen\Core\Form\Type\EditorType;
use Oxygen\Core\Form\Type\NumberType;
use Oxygen\Core\Form\Type\RelationshipType;
use Oxygen\Core\Form\Type\SelectType;

class FormServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */

    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        FieldMetadata::setDefaultType(new BaseType());
        FieldMetadata::addType('checkbox', new BooleanType());
        FieldMetadata::addType('toggle', new BooleanType());
        FieldMetadata::addType('datetime', new DatetimeType());
        FieldMetadata::addType('date', new DateType());
        FieldMetadata::addType('number', new NumberType());
        FieldMetadata::addType('select', new SelectType());
        FieldMetadata::addType('relationship', new RelationshipType());
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return [
        ];
    }

}
