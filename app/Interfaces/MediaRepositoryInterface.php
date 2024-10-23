<?php

namespace App\Interfaces;

interface MediaRepositoryInterface
{
    public function getMediaById($mediaId);

    public function getMediaByUuid($mediaId);

    public function uploadMediaFromRequest($model, $inputControlName, $collectionName);
}
