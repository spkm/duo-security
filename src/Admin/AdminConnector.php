<?php

namespace spkm\DuoSecurity\Admin;

use spkm\DuoSecurity\Admin\Resources\HasResources;
use spkm\DuoSecurity\DuoConnector;

/**
 * The connection to an Admin API application (https://duo.com/docs/adminapi): a resource for every section of the
 * documentation, from users and phones to policies and logs, each reached as DuoSecurity::users() and the like.
 */
class AdminConnector extends DuoConnector
{
    use HasResources;

    protected function refusedRequests(): string
    {
        return 'spkm\\DuoSecurity\\Auth\\Requests\\';
    }
}
