<?php

namespace Oxygen\Core\Form\Type;

use Oxygen\Core\Form\FieldMetadata;

class RelationshipType extends BaseType {

    /**
     * Takes the given input value and transforms it into a compatible value for storage.
     *
     * @param FieldMetadata $metadata
     * @param string $value
     * @return mixed
     */
    public function transformInput(FieldMetadata $metadata, $value) {

        $repo = $metadata->options['repository'];
        $repo = is_callable($repo) ? $repo() : $repo;

        return $repo->getReference((int) $value);
    }

}
