<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    protected SettingsService $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function index()
    {
        $data['setting'] = $this->settingsService->getSettings();
        return view('admin.setting.index', $data);
    }

    public function update(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'app_name' => ['required', 'string'],
                'app_slogan' => ['nullable', 'string'],
                'app_logo' => ['nullable', 'mimes:jpg,jpeg,png,webp,svg,gif', 'max:5120'],
                'app_background_image' => ['nullable', 'mimes:jpg,jpeg,png,webp,svg,gif', 'max:5120'],
                'point_per_coupon' => ['nullable', 'integer'],
                'is_point_by_registration' => ['required', 'boolean'],
                'point_per_registration' => ['nullable', 'integer'],
            ]);

            $this->settingsService->update($validatedData);

            return redirect()
                ->back()
                ->with([
                    'message' => 'Settings updated successfully.',
                    'alert-type' => 'success',
                ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to update settings. Please try again.']);
        }
    }
}
