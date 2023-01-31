<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'tasks',
        'add_update_task',
        'add_member_to_task',
        'delete_task',
        'remove_member_from_task',
        'create_task',
        'logout'
    ];
}
