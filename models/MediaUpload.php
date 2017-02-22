<?php

namespace humhub\modules\gallery\models;

/**
 * MediaUpload
 * 
 * @author Sebastian Stumpf
 */
class MediaUpload extends \humhub\modules\file\models\FileUpload
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            ['uploadedFile', 'file', 'extensions' => ['jpg', 'gif', 'bmp', 'svg', 'tiff', 'png']],
        ];
        return array_merge(parent::rules(), $rules);
    }

}
