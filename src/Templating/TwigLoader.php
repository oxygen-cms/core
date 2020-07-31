<?php


namespace Oxygen\Core\Templating;

use Twig\Error\LoaderError;
use Twig\Loader\LoaderInterface;
use Twig\Source;

class TwigLoader implements LoaderInterface {

    /**
     * @var ResourceLoader[]
     */
    protected $resources = [];
    
    public function preloadItem(Templatable $item) {
        $this->resources[$item->getResourceType()]->preloadItem($item);
    }

    /**
     * @param $resourceName
     * @param ResourceLoader $resourceLoader
     */
    public function addResourceType($resourceName, ResourceLoader $resourceLoader) {
        $this->resources[$resourceName] = $resourceLoader;
    }

    /**
     * @param $resourceName
     * @return ResourceLoader
     * @throws LoaderError
     */
    public function getResourceLoader($resourceName) {
        if(isset($this->resources[$resourceName])) {
            return $this->resources[$resourceName];
        } else {
            throw new LoaderError('resource not found: ' . $resourceName);
        }
    }
    
    public function getSourceContext(string $name): Source {
        $parts = $this->splitName($name);
        $source = $this->getResourceLoader($parts['resource'])->getSourceContext($parts['key']);
        return new Source($source->getCode(), $parts['resource'] . '::' . $source->getName(), $source->getPath());
    }

    public function getCacheKey(string $name): string {
        $parts = $this->splitName($name);
        return $parts['resource'] . '::' . $this->getResourceLoader($parts['resource'])->getCacheKey($parts['key']);
    }

    public function isFresh(string $name, int $time): bool {
        $parts = $this->splitName($name);
        return $this->getResourceLoader($parts['resource'])->isFresh($parts['key'], $time);
    }

    public function exists(string $name) {
        $parts = $this->splitName($name);
        try {
            return $this->getResourceLoader($parts['resource'])->exists($parts['key']);
        } catch(LoaderError $e) {
            return false;
        }
    }
    
    private function splitName($name) {
        $nameParts = explode('::', $name);
        if(count($nameParts) != 2) {
            throw new LoaderError('Invalid template name '. $name);
        }
        return [
            'resource' => $nameParts[0],
            'key' => $nameParts[1]
        ];
    }

}