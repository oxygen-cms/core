<?php


namespace Oxygen\Core\Templating;

use Illuminate\Container\Container;
use Oxygen\Data\Repository\RepositoryInterface;
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
     * @var Templatable
     */
    private ?Templatable $tipTapRootItem = null;

    private $tipTapHtmlConversion;

    /**
     * DoctrineResourceLoader constructor.
     * @param Container $container
     * @param string $repositoryClassName
     * @param callable $tipTapHtmlConversion
     */
    public function __construct(Container $container, string $repositoryClassName, callable $tipTapHtmlConversion) {
        $this->container = $container;
        $this->repositoryClassName = $repositoryClassName;
        $this->tipTapHtmlConversion = $tipTapHtmlConversion;
    }

    /**
     * @return TemplatableRepositoryInterface
     */
    private function getRepository(): TemplatableRepositoryInterface {
        return $this->container[$this->repositoryClassName];
    }

    /**
     * @param string $name
     * @return Source
     * @throws LoaderError
     */
    public function getSourceContext(string $name): Source {
        $item = $this->getByKey($name);
        if($this->tipTapRootItem !== null && $this->tipTapRootItem !== $item) {
            $code = ($this->tipTapHtmlConversion)($this, $name);
        } else {
            $code = $item->getTemplateCode();
            if($code == null) {
                $code = '';
            }
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
        if($this->tipTapRootItem !== null) { return false; }
        $item = $this->getByKey($name);
        return $item->getUpdatedAt()->getTimestamp() < $time;
    }

    /**
     * @param string $name
     * @return bool true if the template exists
     */
    public function exists(string $name) {
        try {
            $this->getByKey($name);
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

        $parts = explode('::', $key);
        $modelId = null;
        if(isset($parts[1]) && is_numeric($parts[1])) {
            $modelId = (int) $parts[1];
        }

        if($modelId !== null) {
            $item = $this->getRepository()->find($modelId);
        } else {
            $item = $this->getRepository()->findByTemplateKey($parts[0], true);
        }

        if($item === null) {
            throw new LoaderError($key . ' not found');
        }
        $this->cache[$key] = $item;
        return $item;
    }

    /**
     * @param Templatable $item
     * @return int|string
     */
    public function preloadItem(Templatable $item) {
        $key = $item->getResourceKey() . '::' . ($item->isPublished() ? 'published' : $item->getId());
        $this->cache[$key] = $item;
        return $key;
    }

    /**
     * @param string $key
     * @return Templatable
     * @throws LoaderError
     */
    public function getLatestItemForKey(string $key) {
        $item = $this->getRepository()->findByTemplateKey($key, false);
        if($item === null) {
            throw new LoaderError($key . ' not found');
        }
        return $item;
    }

    /**
     * @param Templatable $rootItem
     * @return void
     */
    public function setConvertToTipTap(Templatable $rootItem) {
        $this->tipTapRootItem = $rootItem;
    }

}
