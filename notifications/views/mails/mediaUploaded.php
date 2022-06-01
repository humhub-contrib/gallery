<?php

use humhub\modules\gallery\notifications\MediaUploaded;
use humhub\modules\notification\models\Notification;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use humhub\widgets\mails\MailButton;
use humhub\widgets\mails\MailButtonList;
use humhub\widgets\mails\MailContentEntry;
use yii\web\View;

/* @var $this View */
/* @var $viewable MediaUploaded */
/* @var $url string */
/* @var $date string */
/* @var $originator User */
/* @var $space Space */
/* @var $record Notification */
/* @var $_params_ array */
?>
<?php $this->beginContent('@notification/views/layouts/mail.php', $_params_); ?>

    <?= MailContentEntry::widget([
        'originator' => $originator,
        'receiver' => $record->user,
        'content' => Yii::t('GalleryModule.base', 'New files have been uploaded into the gallery "{galleryName}".', [
            'galleryName' => $viewable->source->title
        ]),
        'date' => $date,
        'space' => $space
    ]) ?>

    <?= MailButtonList::widget([
        'buttons' => [
            MailButton::widget(['url' => $url, 'text' => Yii::t('GalleryModule.base', 'View Online')])
        ]
    ]) ?>

<?php $this->endContent();