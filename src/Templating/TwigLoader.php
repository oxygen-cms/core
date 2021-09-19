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

    /**
     * @var bool
     */
    protected $publishedMode = false;

    public function preloadItem(Templatable $item) {
        $key = $this->resources[$item->getResourceType()]->preloadItem($item);
        return $item->getResourceType() . '::' . $key;
    }

    /**
     * @param string $resourceName
     * @param ResourceLoader $resourceLoader
     */
    public function addResourceType($resourceName, ResourceLoader $resourceLoader) {
        $this->resources[$resourceName] = $resourceLoader;
    }

    /**
     * @param string $resourceName
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

    /**
     * @throws LoaderError if the template name is not valid
     */
    private function splitName($name) {
        $nameParts = explode('::', $name);

        if(count($nameParts) < 2) {
            throw new LoaderError('Invalid template name '. $name);
        }

        $resource = array_shift($nameParts);

        if(!$this->publishedMode && count($nameParts) == 1) {
            $item = $this->getResourceLoader($resource)->getLatestItemForKey($nameParts[0]);
            $id = $item->getId();
            $nameParts[] = strval($id);
        }

        return [
            'resource' => $resource,
            'key' => implode('::', $nameParts)
        ];
    }

    public function setPublishedMode(bool $published) {
        $this->publishedMode = $published;
    }

}
