<?php

namespace humhub\modules\gallery\models;

use humhub\modules\file\models\FileUpload;

/**
 * MediaUpload
 * 
 * @author Sebastian Stumpf
 */
class MediaUpload extends FileUpload
{

    /**
     * The supported extensions
     */
    public $validExtensions = ['jpg', 'jpeg', 'gif', 'bmp', 'svg', 'tiff', 'png'];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(),  [
            ['uploadedFile', 'file', 'extensions' => $this->validExtensions],
        ]);
    }

}
    
