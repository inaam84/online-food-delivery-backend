<?php

namespace App\Services\Validation;

class FileValidationService
{
    public static function validateFile($file)
    {
        return validator(
            ['file' => $file],
            ['file' => 'required|file|mimes:jpg,png,jpeg,gif,pdf,doc,docx|max:5120']
        )->validate();
    }

    public static function validateVendorLogo($logo)
    {
        return validator(
            ['logo' => $logo],
            ['logo' => 'required|file|mimes:jpg,png,jpeg,gif|max:5120']
        )->validate();
    }
}
