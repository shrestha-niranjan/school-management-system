<?php

namespace App\Console\Commands;

use App\Actions\PermissionsAction;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class RefreshPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates permissions from the route names.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $newRoutes = Permission::pluck('name');

        $oldRoutes = collect(
            array_values($permissions = (new PermissionsAction)->execute())
        );

        if (!$oldRoutes->diff($newRoutes)->isEmpty()) {
            foreach (
                $permissions as $permission
            ) {
                Permission::query()
                    ->firstOrCreate(
                        ['name' => $permission]
                    );
            }
        }

        if (!$newRoutes->diff($oldRoutes)->isEmpty()) {
            foreach ($newRoutes->diff($oldRoutes) as $permissionName) {
                Permission::findByName($permissionName)
                    ->delete();
            }
        }
    }
}
