<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\MedicalFileRequest;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Log;

class MedicalFileController extends Controller
{
    /**
     * Show the user's medical file settings page.
     */
    public function edit(Request $request)
    {
        $medicalFile = $request->user()->medicalFile;

        return Inertia::render('settings/medical-file', [
            'file' => $medicalFile,
        ]);
    }

    /**
     * Update the user's medical file information.
     */
    public function update(MedicalFileRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();

        try {

            $user->medicalFile()->updateOrCreate([], $validated);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.settings.medical_file.updated_successfully')]);

            return to_route('medical-file.edit');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.settings.medical_file.failed_update')]);
            Log::error('Failed to update medical file', ['user_id' => $user->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }
}
