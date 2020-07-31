<?php


namespace Oxygen\Core\Templating;

use DateTime;

interface Templatable {

    /**
     * @return string
     */
    public function getResourceType();

    /**
     * @return string
     */
    public function getResourceKey();
    
    /**
     * @return string
     */
    public function getTemplateCode();

    /**
     * @return DateTime
     */
    public function getUpdatedAt();

}