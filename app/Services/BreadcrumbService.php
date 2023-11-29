<?php

namespace App\Services;

class BreadcrumbService
{
    public function generate()
    {
        $segments = request()->segments();

        $url = '';
        $breadcrumbs = [
            'home' => [
                'icon' => 'pi pi-home',
                'route' => '/'
            ],
        ];

        foreach ($segments as $segment) {
            if ($segment === 'dashboard' || is_numeric($segment)) {
                continue;
            }

            $url .= '/' . $segment;

            $breadcrumbs['items'][] = [
                'label' => ucwords(str_replace('-', ' ', $segment)),
                'route' => $url,
            ];
        }

        return $breadcrumbs;
    }
}
