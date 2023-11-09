<?php

use App\Models\SchoolSetting;

if (!function_exists('school_setting')) {
    function school_setting() : SchoolSetting
    {
        return SchoolSetting::first();
    }
}
