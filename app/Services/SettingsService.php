<?php

namespace App\Services;

use App\Models\Setting;

class SettingsService
{
    public function getSettings()
    {
        return Setting::latest()->first();
    }

    public function update($input)
    {
        $setting = $this->getSettings();

        if (isset($input['app_logo'])) {
            if ($setting && $setting->app_logo) {
                deleteFile($setting->app_logo);
            }
            $input['app_logo'] = uploadFile($input['app_logo'], 'app_logo');
        }

        if (isset($input['app_background_image'])) {
            if ($setting && $setting->app_background_image) {
                deleteFile($setting->app_background_image);
            }
            $input['app_background_image'] = uploadFile($input['app_background_image'], 'app_background_image');
        }

        if ($setting) {
            return $setting->update($input);
        } else {
            return Setting::create($input);
        }
    }
}
