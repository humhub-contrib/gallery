<?php

namespace humhub\modules\calendar\tests\codeception\unit\reminder;

use humhub\modules\gallery\models\CustomGallery;
use humhub\modules\gallery\models\Media;
use humhub\modules\space\models\Space;
use tests\codeception\_support\HumHubDbTestCase;

class CustomGalleryTest extends HumHubDbTestCase
{
    public function testCreateGallery()
    {
        $space = Space::findOne(1);
        $this->becomeUser('User1');
        $gallery = new CustomGallery(Space::findOne(1), [
            'title' => 'Test Gallery',
            'description' => 'Test Gallery Description'
        ]);

        $this->assertTrue($gallery->save());

        $media = new Media($space, [
            'gallery' => $gallery,
            'title' => 'My media',
            'description' => 'My test media'
        ]);

        $this->assertTrue($media->save());

        $gallery->refresh();
        $medias = $gallery->getMediaList();

        $this->assertCount(1, $medias);
        $this->assertEquals($media->title, $medias[0]->title);
    }

    public function testDeleteGallery()
    {
        $space = Space::findOne(1);
        $this->becomeUser('User1');
        $gallery = new CustomGallery(Space::findOne(1), [
            'title' => 'Test Gallery',
            'description' => 'Test Gallery Description'
        ]);

        $this->assertTrue($gallery->save());

        $media = new Media($space, [
            'gallery' => $gallery,
            'title' => 'My media',
            'description' => 'My test media'
        ]);

        $this->assertTrue($media->save());

        $gallery->refresh();
        $this->assertEquals(1, $gallery->delete());

        $this->assertCount(0, Media::find()->all());
    }
}