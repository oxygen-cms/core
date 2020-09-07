<?php


namespace Oxygen\Core\Templating;


use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\SandboxExtension;
use Twig\Loader\ArrayLoader;
use Twig\Loader\ChainLoader;

class TwigTemplateCompiler {

    /**
     * @var TwigLoader
     */
    private $loader;
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var ArrayLoader
     */
    private $stringLoader;
    /**
     * @var string[]
     */
    private $allowedFunctions;
    /**
     * @var string[]
     */
    private $allowedMethods;
    
    /**
     * @var \Twig\Sandbox\SecurityPolicy
     */
    private $policy;

    public function __construct($cachePath) {
        $this->loader = new TwigLoader();
        $this->stringLoader = new ArrayLoader();
        $this->twig = new Environment(new ChainLoader([$this->stringLoader, $this->loader]), [
            'cache' => $cachePath,
            'auto_reload' => true,
            'strict_variables' => true
        ]);
        $tags = ['if', 'for', 'set'];
        $filters = ['upper', 'escape', 'raw'];
        $this->allowedMethods = [];
        $properties = [
            //'Article' => ['title', 'body'],
        ];
        $this->allowedFunctions = ['include'];
        $this->policy = new \Twig\Sandbox\SecurityPolicy($tags, $filters, $this->allowedMethods, $properties, $this->allowedFunctions);
        $this->twig->addExtension(new SandboxExtension($this->policy, true));
    }
    
    public function getTwig() {
        return $this->twig;
    }

    public function addAllowedFunction($function) {
        $this->allowedFunctions[] = $function;
        $this->policy->setAllowedFunctions($this->allowedFunctions);
    }

    public function setAllowedMethods($class, $methods) {
        $this->allowedMethods[$class] = $methods;
        $this->policy->setAllowedMethods($this->allowedMethods);
    }
    
    /**
     * @return TwigLoader
     */
    public function getLoader() {
        return $this->loader;
    }

    /**
     * Renders a template.
     *
     * @param Templatable $item
     * @param array $params
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(Templatable $item, array $params = []) {
        $this->getLoader()->preloadItem($item);
        return $this->twig->render($item->getResourceType() . '::' . $item->getResourceKey(), $params);
    }

    /**
     * @param string $template
     * @param string|null $key
     * @param array $params
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderString($template, string $key = null, array $params = []) {
        if($template === null) {
            $template = '';
        }
        if($key === null) {
            $key = hash('sha256', $template);
        }
        $this->stringLoader->setTemplate($key, $template);
        return $this->twig->render($key, $params);
    }

}