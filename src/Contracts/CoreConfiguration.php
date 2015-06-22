<?php

namespace Oxygen\Core\Contracts;

interface CoreConfiguration {

    /**
     * Returns the base URI to be used by Oxygen
     *
     * @return mixed
     */
    public function getAdminURIPrefix();

    /**
     * Returns the base URI to be used by Oxygen
     *
     * @return mixed
     */
    public function getLayout();

}
