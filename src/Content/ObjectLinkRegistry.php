<?php

namespace Oxygen\Core\Content;

class ObjectLinkRegistry {

    /**
     * @var ObjectLinkType[]
     */
    public $types = [];

    public function addType(ObjectLinkType $type) {
        $this->types[] = $type;
    }

    public function getParseConfig(): array {
        $config = [];
        foreach($this->types as $type) {
            $config = array_merge($config, $type->getParseConfig());
        }
        return $config;
    }

    /**
     * @throws \Exception
     */
    public function resolve(string $ty, int $id): array {
        foreach($this->types as $type) {
            if($type->getName() == $ty) {
                return $type->resolveLink($id);
            }
        }
        throw new \Exception('unknown object type ' . $ty);
    }
}