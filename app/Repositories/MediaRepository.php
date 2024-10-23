<?php

namespace App\Repositories;

use App\Interfaces\MediaRepositoryInterface;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaRepository implements MediaRepositoryInterface
{
    public function getMediaById($mediaId)
    {
        return Media::find($mediaId);
    }

    public function getMediaByUuid($mediaId)
    {
        return Media::findByUuid($mediaId);
    }

    public function uploadMediaFromRequest($model, $inputControlName, $collectionName)
    {
        $model
            ->addMediaFromRequest($inputControlName)
            ->sanitizingFileName(function ($fileName) {
                return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
            })
            ->toMediaCollection($collectionName);
    }
}