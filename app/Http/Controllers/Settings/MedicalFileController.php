<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\MedicalFileRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

        $user->medicalFile()->updateOrCreate([], $validated);

        return to_route('profile.edit');
    }
}
