<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\LocaleUpdateRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Log;

class LocaleController extends Controller
{
    /**
     * Update the user's preferred locale.
     */
    public function __invoke(LocaleUpdateRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $locale = $validated['locale'];

            $request->user()->update(['locale' => $locale]);

            App::setLocale($locale);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.settings.profile.language.updated_successfully')]);

            return back();
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.settings.profile.language.failed_update')]);
            Log::error('Failed to update locale', ['user_id' => $request->user()->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }
}
