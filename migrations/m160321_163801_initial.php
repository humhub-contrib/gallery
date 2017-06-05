<?php

use \yii\db\Migration;

class m160321_163801_initial extends Migration
{

    public function up()
    {
        $this->createTable('gallery_media', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'gallery_id' => $this->integer(11),
            'description' => $this->string(1000),
            'sort_order' => $this->integer(11)->defaultValue(0)
        ], '');

        $this->createTable('gallery_gallery', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'description' => $this->string(1000),
            'sort_order' => $this->integer(11)->defaultValue(0),
            'thumb_file_id' => $this->integer(11),
            'type' => $this->string(255),
        ], '');
    }

    public function down()
    {
        echo "m160321_163801_initial cannot be reverted.\n";

        return false;
    }

    /*
     * // Use safeUp/safeDown to run migration code within a transaction public function safeUp() { } public function safeDown() { }
     */
}
