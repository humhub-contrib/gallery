<?php

use yii\db\Migration;

class uninstall extends Migration
{
    public function up()
    {
        $this->dropTable('gallery_media');
        $this->dropTable('gallery_gallery');
    }

    public function down()
    {
        echo "uninstall does not support migration down.\n";
        return false;
    }

}
