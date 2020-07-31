<?php


namespace Oxygen\Core\Templating;

use Illuminate\Container\Container;
use OxygenModule\Pages\Entity\Page;
use Twig\Error\LoaderError;
use Twig\Source;

class DoctrineResourceLoader implements ResourceLoader {

    /**
     * @var string
     */
    private $repositoryClassName;

    /**
     * @var Templatable[]
     */
    private $cache;
    /**
     * @var Container
     */
    private $container;

    /**
     * DoctrineResourceLoader constructor.
     * @param Container $container
     * @param string $repositoryClassName
     */
    public function __construct(Container $container, string $repositoryClassName) {
        $this->container = $container;
        $this->repositoryClassName = $repositoryClassName;
    }

    /**
     * @param string $name
     * @return Source
     * @throws LoaderError
     */
    public function getSourceContext(string $name): Source {
        $item = $this->getByKey($name);
        $code = $item->getTemplateCode();
        if($code === null) {
            $code = '';
        }
        return new Source($code, $name);
    }

    public function getCacheKey(string $name): string {
        return $name;
    }

    /**
     * @param string $name
     * @param int $time
     * @return bool
     * @throws LoaderError
     */
    public function isFresh(string $name, int $time): bool {
        $item = $this->getByKey($name);
//        dd($item->getUpdatedAt()->getTimestamp(), $time);
        return $item->getUpdatedAt()->getTimestamp() < $time;
    }

    /**
     * @param string $name
     * @return bool true if the template exists
     */
    public function exists(string $name) {
        try {
            $item = $this->getByKey($name);
            return true;
        } catch (LoaderError $e) {
            return false;
        }
    }

    /**
     * @param string $key
     * @return Templatable|null
     * @throws LoaderError
     */
    public function getByKey(string $key) {
        if(isset($this->cache[$key])) {
            return $this->cache[$key];
        }
        
        $item = $this->container[$this->repositoryClassName]->findByTemplateKey($key);
        if($item === null){
            throw new LoaderError($key . ' not found');
        }
        $this->cache[$key] = $item;
        return $item;
    }

    public function preloadItem(Templatable $item) {
        $this->cache[$item->getResourceKey()] = $item;
    }
}