<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = $request->user();
        if (!in_array($permission, $this->permissions()[$user->role->name] ?? [])) {
            return response()->json(['error' => 'Permission denied'], 403);
        }

        return $next($request);
    }

    private function permissions()
    {
        return [
            'admin' => [
                'create-user',
                'delete-user',
                'update-user',
                'view-users',
                'manage-roles',
            ],
        ];
    }
}
