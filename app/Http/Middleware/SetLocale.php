<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Country headers commonly added by proxies or edge platforms.
     *
     * @var list<string>
     */
    private const array COUNTRY_HEADERS = [
        'Accept-Language',
        'CF-IPCountry',
        'CloudFront-Viewer-Country',
        'X-Appengine-Country',
        'X-Vercel-IP-Country',
        'X-Country-Code',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        App::setLocale($this->resolveLocale($request));

        return $next($request);
    }

    private function resolveLocale(Request $request): string
    {
        return $this->normalizeLocale($request->user()?->locale)
            ?? $this->resolveRequestLocale($request)
            ?? $this->normalizeLocale(config('app.locale'))
            ?? 'en';
    }

    private function resolveRequestLocale(Request $request): ?string
    {
        if ($this->requestIndicatesBrazil($request)) {
            return 'pt_BR';
        }

        $preferredLanguage = $request->getPreferredLanguage();

        if ($preferredLanguage === null) {
            return null;
        }

        return $this->normalizeLocale($preferredLanguage);
    }

    private function requestIndicatesBrazil(Request $request): bool
    {
        foreach (self::COUNTRY_HEADERS as $header) {
            $countryCode = $request->header($header);

            if ($countryCode !== null && Str::upper($countryCode) === 'BR') {
                return true;
            }
        }

        return false;
    }

    private function normalizeLocale(?string $locale): ?string
    {
        if ($locale === null || $locale === '') {
            return null;
        }

        $normalizedLocale = Str::of($locale)
            ->replace('-', '_')
            ->lower()
            ->value();

        return match ($normalizedLocale) {
            'pt_br' => 'pt_BR',
            default => 'en',
        };
    }
}
