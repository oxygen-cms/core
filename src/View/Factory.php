<?php

namespace Oxygen\Core\View;

use Illuminate\View\Factory as BaseFactory;

class Factory extends BaseFactory {

    /**
     * Get the evaluated view contents for the given string.
     *
     * @param  string  $contents
     * @param  string  $unique
     * @param  int     $timestamp
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View
     */
    public function string($contents, $unique, $timestamp, $data = [], $mergeData = []) {
        $data = array_merge($mergeData, $this->parseData($data));

        $this->callCreator($view = new StringView($this, $this->engines->resolve('blade.string'), $contents, $unique, $timestamp, $data));

        return $view;
    }

    /**
     * Get the evaluated view contents for the given model and field.
     * If the model doesn't use timestamps then the view will be re-compiled on every request.
     *
     * @param  object  $model
     * @param  string  $field
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View
     */
    public function model($model, $field, $data = [], $mergeData = []) {
        $contents = $model->getAttribute($field);
        $path = $this->pathFromModel(get_class($model), $model->getId(), $field);
        $timestamp = class_uses($model, 'Oxygen\Data\Behaviour\Timestamps') ? $model->getAttribute('updatedAt')->getTimestamp() : 0;
        
        return $this->string($contents, $path, $timestamp, $data, $mergeData);
    }

    /**
     * Generates a unique path from a model.
     *
     * @param        $className
     * @param        $id
     * @param string $field
     * @return string
     */
    public function pathFromModel($className, $id, $field) {
        $path = 'db_' . $className . '_' . $id . '_' . $field;
        return strtolower(str_replace(['-', '.'], '_', $path));
    }

}
