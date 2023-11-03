<?php

namespace App\Actions;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

class PermissionsAction
{
    /**
     *  Provides routes presented by the system as a permissions
     *
     * @return array
     */
    public function execute(): array
    {
        $routes = Route::getRoutes()->get();

        $hiddenRoutes = $this->hiddenRoutes();

        foreach ($routes as $route) {
            if (
                $route->getName() != null &&
                    !in_array(
                        $route->getName(),
                        $hiddenRoutes
                    )
            ) {
                $routeName = str_replace('admin.', ' ', $route->getName());

                $list = str_replace('.', ' ', $routeName);

                $permissions[$routeName] = trim(str_replace(
                    'destroy',
                    'delete',
                    str_replace('index', 'list', $list)
                ));

                if (!str_contains($permissions[$routeName], 'list')) {
                    $explodeUnorderedPermissionName = explode(' ', $permissions[$routeName]);

                    $orderedPermissionNameArray = [];
                    foreach ($explodeUnorderedPermissionName as $name) {
                        if (!empty($name)) {
                            array_push($orderedPermissionNameArray, Str::singular($name));
                        }
                    }

                    $permissions[$routeName] = implode(' ', array_reverse($orderedPermissionNameArray));
                }
            }
        }

        return
            array_filter($permissions);
    }

        /**
     * hiddenRoutes
     *
     * @return array
     */
    private function hiddenRoutes(): array
    {
        return [
            'ignition.executeSolution',
            'ignition.healthCheck',
            'ignition.scripts',
            'ignition.shareReport',
            'ignition.styles',
            'ignition.updateConfig',
            'sanctum.csrf-cookie',
            'home',
            'passport.authorizations.authorize',
            'passport.authorizations.approve',
            'passport.authorizations.deny',
            'passport.clients.index',
            'passport.clients.update',
            'passport.clients.store',
            'passport.clients.destroy',
            'passport.personal.tokens.index',
            'passport.personal.tokens.store',
            'passport.personal.tokens.destroy',
            'passport.scopes.index',
            'passport.token',
            'passport.token.refresh',
            'passport.tokens.index',
            'passport.tokens.destroy'
        ];
    }
}
