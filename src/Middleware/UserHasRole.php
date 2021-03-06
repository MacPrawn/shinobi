<?php

namespace Caffeinated\Shinobi\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class UserHasRole
{
    /**
     * @var Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create a new UserHasPermission instance.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Run the request filter.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $closure
     * @param string                   $role
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $roles = implode(',', array_slice(func_get_args(), 2));
        if (!$this->auth->user()->isRole($roles)) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            }

            return abort(401);
        }

        return $next($request);
    }
}
