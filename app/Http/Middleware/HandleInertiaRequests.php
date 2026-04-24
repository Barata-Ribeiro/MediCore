<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

use function in_array;
use function syncLangFiles;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    protected array $authRoutes = [
        'login', 'register', 'password.request', 'password.reset',
        'password.confirm', 'verification.notice', 'two-factor.login',
    ];

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        syncLangFiles('main');

        if (in_array($request->route()?->getName(), $this->authRoutes)) {
            syncLangFiles('auth_pages');
        }

        $now = now();

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user()?->load('roles:id,name'),
                'permissions' => fn () => $request->user()?->getAllPermissions()->pluck('name'),
                'locale' => app()->currentLocale(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'date' => [
                'now' => $now->toISOString(),
                'displayDate' => $now->format('F j, Y'),
                'greeting' => match (true) {
                    $now->hour >= 5 && $now->hour < 12 => 'Good morning',
                    $now->hour >= 12 && $now->hour < 18 => 'Good afternoon',
                    default => 'Good evening',
                },
            ],
        ];
    }
}
