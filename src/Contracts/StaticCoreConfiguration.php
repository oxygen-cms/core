<?php


namespace Oxygen\Core\Contracts;


class StaticCoreConfiguration implements CoreConfiguration {

    /**
     * Returns the base URI to be used by Oxygen
     *
     * @return mixed
     */
    public function getAdminURIPrefix() {
        return 'oxygen/view';
    }

    /**
     * Returns the base URI to be used by Oxygen
     *
     * @return mixed
     */
    public function getAdminLayout() {
        return 'oxygen/ui-base::layout.main';
    }
}
