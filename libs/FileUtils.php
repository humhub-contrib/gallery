<?php

namespace humhub\modules\gallery\libs;

use \humhub\modules\comment\models\Comment;
use \humhub\modules\content\models\Content;
use \humhub\modules\file\models\File;

/**
 * This is a utility lib for files.
 * 
 * @package humhub.modules.gallery.libs
 * @since 1.0
 * @author Sebastian Stumpf
 */
class FileUtils
{    
    /**
     * Get the content model the file is connected to.
     * @param File $file the file.
     */
    public static function getBaseContent($file = null)
    {
        if ($file === null) {
            return null;
        }
        $searchItem = $file;
        // if the item is connected to a Comment, we have to search for the corresponding Post
        if ($file->object_model === Comment::class) {
            $searchItem = Comment::findOne([
                        'id' => $file->object_id
            ]);
        }
        $query = Content::find();
        $query->andWhere([
            'content.object_id' => $searchItem->object_id,
            'content.object_model' => $searchItem->object_model
        ]);
        return $query->one();
    }

    /**
     * Get the comment, post or other model via which the file was uploaded and is connected to.
     * @param File $file the file.
     */
    public static function getBaseObject($file = null)
    {
        if ($file === null) {
            return null;
        }
        $object = call_user_func([$file->object_model, 'findOne'], [
            'id' => $file->object_id
        ]);

        return $object;
    }

}
