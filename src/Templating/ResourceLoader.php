<?php


namespace Oxygen\Core\Templating;


use Twig\Loader\LoaderInterface;

interface ResourceLoader extends LoaderInterface {
    
    public function preloadItem(Templatable $item);
    
}