<?php

use yii\db\Migration;

class m180307_205154_media_href extends Migration
{

    public function safeUp()
    {
        $this->addColumn('gallery_media', 'href', $this->string(1000));
        return true;
    }

    public function safeDown()
    {
        $this->dropColumn('gallery_media', 'href');
        return true;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180307_205154_media_href cannot be reverted.\n";

      return false;
      }
     */
}
