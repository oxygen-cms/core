<?php

namespace Oxygen\Core;

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
