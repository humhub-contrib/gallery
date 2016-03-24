<?php
namespace humhub\modules\gallery\libs;

use Yii;

/**
 * This is a utility lib for files.
 * 
 * @package humhub.modules.gallery.libs
 * @since 1.0
 * @author Sebastian Stumpf
 */
class FileUtils
{

    public static $map = [
        'code' => [
            'ext' => [
                'html',
                'cmd',
                'bat',
                'xml'
            ],
            'icon' => 'fa-file-code-o'
        ],
        'archive' => [
            'ext' => [
                'zip',
                'rar',
                'gz',
                'tar'
            ],
            'icon' => 'fa-file-archive-o'
        ],
        'audio' => [
            'ext' => [
                'mp3',
                'wav'
            ],
            'icon' => 'fa-file-audio-o'
        ],
        'excel' => [
            'ext' => [
                'xls',
                'xlsx'
            ],
            'icon' => 'fa-file-excel-o'
        ],
        'image' => [
            'ext' => [
                'jpg',
                'gif',
                'bmp',
                'svg',
                'tiff',
                'png'
            ],
            'icon' => 'fa-file-image-o'
        ],
        'pdf' => [
            'ext' => [
                'pdf'
            ],
            'icon' => 'fa-file-pdf-o'
        ],
        'powerpoint' => [
            'ext' => [
                'ppt',
                'pptx'
            ],
            'icon' => 'fa-file-powerpoint-o'
        ],
        'text' => [
            'ext' => [
                'txt',
                'log',
                'md'
            ],
            'icon' => 'fa-file-text-o'
        ],
        'video' => [
            'ext' => [
                'mp4',
                'mpeg',
                'swf'
            ],
            'icon' => 'fa-file-video-o'
        ],
        'word' => [
            'ext' => [
                'doc',
                'docx'
            ],
            'icon' => 'fa-file-word-o'
        ],
        'default' => [
            'ext' => [],
            'icon' => 'fa-file-o'
        ]
    ];

    /**
     * Get the extensions font awesome icon class.
     *
     * @param string $ext
     *            the extension.
     * @return string the font awesome icon class for this extension.
     */
    public static function getIconClassByExt($ext = '')
    {
        foreach (self::$map as $type => $info) {
            if (in_array(strtolower($ext), $info['ext'])) {
                return $info['icon'];
            }
        }
        return self::$map['default']['icon'];
    }

    /**
     * Sanitize a filename.
     *
     * @param string $file_name            
     * @return string
     */
    public static function sanitizeFilename($filename)
    {
        $file = new \humhub\modules\file\models\File();
        $file->file_name = $filename;
        $file->sanitizeFilename();
        return $file->file_name;
    }

    /**
     * Get the extensions type.
     *
     * @param string $ext
     *            the extension.
     * @return string the type or 'unknown'.
     */
    public static function getItemTypeByExt($ext)
    {
        foreach (self::$map as $type => $info) {
            if (in_array(strtolower($ext), $info['ext'])) {
                return $type;
            }
        }
        return 'unknown';
    }
}
