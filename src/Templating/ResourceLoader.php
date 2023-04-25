<?php


namespace Oxygen\Core\Templating;


use Twig\Loader\LoaderInterface;

interface ResourceLoader extends LoaderInterface {

    /**
     * @param Templatable $item
     * @return string the key
     */
    public function preloadItem(Templatable $item);

    /**
     * @param string $key
     * @return Templatable
     */
    public function getLatestItemForKey(string $key);

    /**
     * @param Templatable $rootItem
     * @return void
     */
    public function setConvertToTipTap(Templatable $rootItem);
}
