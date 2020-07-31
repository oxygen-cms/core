<?php


namespace Oxygen\Core\Templating;


interface TemplatableRepositoryInterface {

    /**
     * @param string $key
     * @return Templatable|null
     */
    public function findByTemplateKey($key);

}