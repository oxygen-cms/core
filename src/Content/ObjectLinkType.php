<?php

namespace Oxygen\Core\Content;

interface ObjectLinkType {
    public function getName(): string;
    public function getParseConfig(): array;
    public function parse(\DOMElement $DOMNode): ?array;
    public function resolveLink(int $id): array;
}