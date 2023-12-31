<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Services\BreadcrumbService;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'schoolSetting' => school_setting()
            ],
            'breadcrumbs' => (new BreadcrumbService)->generate(),
            'menus' => [
                [
                    'label' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon' => 'mdiMonitor',
                    'isVisible' => true
                ],
                [
                    'label' => 'Student Management',
                    'route' => 'students.index',
                    'icon' => 'mdiAccountGroup',
                    'isVisible' => true,
                ],
                [
                    'label' => 'Marks Entry',
                    'route' => 'mark-entry.index',
                    'icon' => 'mdiCashRegister',
                    'isVisible' => true,
                ],
                [
                    'label' => 'Marks Ledger',
                    'route' => 'mark-ledger.index',
                    'icon' => 'mdiCashRegister',
                    'isVisible' => true,
                ],
                [
                    'label' => 'Grade Ledger',
                    'route' => 'grade-ledger.index',
                    'icon' => 'mdiCashRegister',
                    'isVisible' => true,
                ],
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];
    }
}
