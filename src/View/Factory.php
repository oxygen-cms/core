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
     * @return Illuminate\View\View
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
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $field
     * @param  array   $data
     * @param  array   $mergeData
     * @return Illuminate\View\View
     */
    public function model($model, $field, $data = [], $mergeData = []) {
        $contents = $model->$field;
        $path = $this->pathFromModel($model, $field);
        $timestamp = $model->usesTimestamps() ? strtotime($model->{$model->getUpdatedAtColumn()}) : 0;

        return $this->string($contents, $path, $timestamp, $data, $mergeData);
    }

    /**
     * Generates a unique path from a model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $field
     * @return string
     */

    protected function pathFromModel($model, $field) {
        $path = 'db_' . $model->getTable() . '_' . $model->getKey() . '_' . $field;
        return strtolower(str_replace(['-', '.'], '_', $path));
    }

}