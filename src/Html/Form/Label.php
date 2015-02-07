<?php

namespace Oxygen\Core\Html\Form;

use Oxygen\Core\Form\FieldMetadata;
use Oxygen\Core\Html\RenderableTrait;

class Label {

    use RenderableTrait;

    protected $metadata;

    public function __construct(FieldMetadata $metadata) {
        $this->metadata = $metadata;
    }

    /**
     * Returns the field metadata
     *
     * @return FieldMetadata
     */
    public function getMeta() {
        return $this->metadata;
    }

}
