<?php

declare(strict_types = 1);

namespace TeamWorkPm\Rest\Resource;

use TeamWorkPm\Factory;
use TeamWorkPm\ArrayObject;

trait UploadTrait
{
    private function uploadFiles(ArrayObject $data)
    {
        $files = $data->pull('files');
        if ($files !== null) {
            $data['pending_file_attachments'] = Factory::file()
                ->upload($files);
        }
    }
}