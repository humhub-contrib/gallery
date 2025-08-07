<?php

namespace gallery;

use Codeception\Util\Locator;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \AcceptanceTester
{
    use _generated\AcceptanceTesterActions;

    public function enableModule($guid, $moduleId)
    {
        $this->amOnSpace($guid, '/space/manage/module');
        $this->seeElement('.enable-module-' . $moduleId);
        $this->jsClick('.enable-module-' . $moduleId);
        $this->waitForElement('.disable-module-' . $moduleId);
        $this->amOnSpace($guid);
    }

    public function enableSpaceModule()
    {
        $this->amAdmin();
        $this->wantToTest('the enabling of the gallery module');
        $this->amGoingTo('install the gallery module for space 1');
        $this->enableModule(1, 'gallery');
        $this->amUser2(true);
        $this->amOnSpace1();
    }

    public function seeGalleryInCotnainerNav()
    {
        $this->expectTo('see gallery entry in the nav');
        $this->waitForText('Gallery', 10, '.layout-nav-container');
    }

    public function accessGallery()
    {
        $this->click('Gallery', '.layout-nav-container');
        $this->waitForText('List of galleries');
        $this->see('Posted Media Files');
    }

    public function createGallery($title = 'Test gallery', $description = 'My test gallery', $public = true)
    {
        $this->click('Click here to add new Gallery');
        $this->waitForText('Add new gallery', 10, '#globalModal');
        $this->fillField('#customgallery-title', $title);
        $this->fillField('#customgallery-description', $description);

        if ($public) {
            $this->click('label', '#globalModal .field-galleryeditform-visibility');
        }

        $this->click('Save', '#globalModal');
        $this->waitForText('Gallery ' . $title, 10, '#gallery-container .panel-heading');

        if ($public) {
            $this->see('Public');
        } else {
            $this->dontSee('Public');
        }
    }

    public function uploadMedia($file = 'logo.jpg', $shouldFail = false)
    {
        $this->attachFile('#gallery-media-upload', $file);
        if (!$shouldFail) {
            $this->waitForElementVisible(Locator::elementAt('.gallery-list-entry', 2));
        }
    }

    public function editMedia()
    {
        $this->clickGalleryItemDropDown('Edit');
        $this->waitForText('Edit media', 10, '#globalModal');
        $this->fillField('#media-description', 'My new media!');
        $this->click('Save', '#globalModal');
        $this->seeSuccess('Saved');
    }

    public function seeMediaFromStream()
    {
        $this->click('Stream', '.layout-nav-container');
        $this->waitForText('My new media!', 10, '.wall-entry');
        $this->click('Open Gallery', '.wall-entry');
        $this->waitForText('Gallery Test gallery');
    }

    public function dontseeMediaInStream()
    {
        $this->click('Stream', '.layout-nav-container');
        $this->waitForElementVisible('#wallStream .wall-entry');
        $this->dontSee('My new media!', '.wall-entry');
    }

    public function deleteGalleryItem()
    {
        $this->clickGalleryItemDropDown('Delete');
        $this->waitForText('Confirm delete item', 10, '#globalModalConfirm');
        $this->click('Confirm', '#globalModalConfirm');
    }

    public function clickGalleryItemDropDown($item)
    {
        $this->wait(2);
        $this->click('.gallery-list-entry .dropdown-toggle');
        $this->wait(1);
        $this->click($item, '.gallery-list-entry .dropdown');
    }

    public function deleteGallery()
    {
        $this->waitForElementVisible(Locator::elementAt('.gallery-list-entry', 3));
        $this->deleteGalleryItem();
        $this->waitForElementNotVisible(Locator::elementAt('.gallery-list-entry', 3));
    }

    public function deleteMedia()
    {
        $this->waitForElementVisible(Locator::elementAt('.gallery-list-entry', 2));
        $this->deleteGalleryItem();
        $this->waitForElementNotVisible(Locator::elementAt('.gallery-list-entry', 2));
    }
}
