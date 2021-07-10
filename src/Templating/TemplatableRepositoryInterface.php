<?php


namespace Oxygen\Core\Templating;


use Oxygen\Data\Repository\RepositoryInterface;

interface TemplatableRepositoryInterface extends RepositoryInterface {

    /**
     * @param string $key
     * @param bool $onlyPublished whether to only consider published items when templating
     * @return Templatable|null
     */
    public function findByTemplateKey($key, $onlyPublished = true);

}
