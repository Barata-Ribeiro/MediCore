<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\LocaleUpdateRequest;
use App\Http\Requests\Settings\ProfileDeleteRequest;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Log;

use function array_key_exists;

class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
            'profile' => $request->user()->profile()->first(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validated();

        try {
            $user->fill($validated);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            DB::transaction(function () use ($user, $validated) {
                $profileData = array_filter(
                    [
                        'first_name' => $validated['first_name'] ?? null,
                        'last_name' => $validated['last_name'] ?? null,
                        'bio' => $validated['bio'] ?? null,
                        'birth_date' => $validated['birth_date'] ?? null,
                        'phone_number' => $validated['phone_number'] ?? null,
                        'address' => $validated['address'] ?? null,
                        'sex' => $validated['sex'] ?? null,
                        'gender_identity' => $validated['gender_identity'] ?? null,
                    ],
                    fn ($value) => $value !== null
                );

                if (array_key_exists('birth_date', $profileData)) {
                    $profileData['birth_date'] = Carbon::parse($profileData['birth_date'])->toDateString();
                }

                if (! empty($profileData)) {
                    $user->profile()->updateOrCreate([], $profileData);
                }

                $user->save();
            });

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.settings.profile.update.updated_successfully')]);

            return to_route('profile.edit');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.settings.profile.update.failed_update')]);
            Log::error('Failed to update profile', ['user_id' => $user->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    /**
     * Update the user's preferred locale.
     */
    public function updateLocale(LocaleUpdateRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $locale = $validated['locale'];

            $request->user()->update(['locale' => $locale]);

            App::setLocale($locale);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.settings.profile.language.updated_successfully')]);

            return to_route('dashboard');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.settings.profile.language.failed_update')]);
            Log::error('Failed to update locale', ['user_id' => $request->user()->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    /**
     * Delete the user's profile.
     */
    public function destroy(ProfileDeleteRequest $request): RedirectResponse
    {
        $user = $request->user();

        try {
            Auth::logout();

            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.settings.profile.destroy.deleted_successfully')]);

            return redirect('/');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.settings.profile.destroy.failed_delete')]);
            Log::error('Failed to delete account', ['user_id' => $user->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }
}
