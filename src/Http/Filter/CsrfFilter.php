<?php

namespace Oxygen\Core\Http\Filter;

use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;
use Illuminate\Session\TokenMismatchException;

class CsrfFilter {

    /**
     * SessionManager dependency.
     *
     * @var SessionManager
     */

    protected $session;

    /**
     * Constructs the PermissionsFilter
     *
     * @param SessionManager    $session    SessionManager instance
     */

    public function __construct(SessionManager $session) {
        $this->session = $session;
    }

    /**
     * The CSRF filter is responsible for protecting your application against
     * cross-site request forgery attacks. If this special token in a user
     * session does not match the one given in this request, we'll bail.
     */

    public function filter($route, $request) {
        if ($this->session->token() != $request->get('_token')) {
            throw new TokenMismatchException();
        }
    }

}