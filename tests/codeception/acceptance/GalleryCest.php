<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace gallery\acceptance;

use Codeception\Util\Locator;
use gallery\AcceptanceTester;

class GalleryCest
{
    private function enableSpaceModule(AcceptanceTester $I)
    {
        $I->amAdmin();
        $I->enableModule(1, 'gallery');
        $I->wait(5);
        $I->jsClick('[data-menu-id="account-logout"]');
        $I->wait(5);
        $I->amUser2();
        $I->amOnSpace1();
    }

    private function seeGalleryInCotnainerNav(AcceptanceTester $I)
    {
        $I->expectTo('see gallery entry in the nav');
        $I->waitForText('Gallery', null, '.layout-nav-container');
    }

    private function accessGallery(AcceptanceTester $I)
    {
        $I->click('Gallery', '.layout-nav-container');
        $I->waitForText('List of galleries');
        $I->see('Posted Media Files');
    }

    private function createGallery(AcceptanceTester $I, $title = 'Test gallery', $description = 'My test gallery', $public = true)
    {
        $I->click('.add-entry');
        $I->waitForText('Add new gallery', null, '#globalModal');
        $I->fillField('#customgallery-title', $title);
        $I->fillField('#customgallery-description', $description);

        if ($public) {
            $I->click('[for="galleryeditform-visibility"]', '#globalModal');
        }

        $I->click('Save', '#globalModal');
        $I->waitForText('Gallery '.$title, null, '#gallery-container .panel-heading');

        if($public) {
            $I->see('Public');
        } else {
            $I->dontSee('Public');
        }
    }

    private function uploadMedia(AcceptanceTester $I, $file = 'logo.jpg', $shouldFail = false)
    {
        $I->attachFile('#gallery-media-upload', $file);
        if(!$shouldFail) {
            $I->waitForElementVisible(Locator::elementAt('.gallery-list-entry', 2));
        }
    }

    private function editMedia(AcceptanceTester $I)
    {
        $this->clickGalleryItemDropDown($I,'Edit');
        $I->waitForText('Edit media', null, '#globalModal');
        $I->fillField('#media-description', 'My new media!');
        $I->click('Save', '#globalModal');
        $I->seeSuccess('Saved');
    }

    private function seeMediaInStream(AcceptanceTester $I)
    {
        $I->click('Stream', '.layout-nav-container');
        $I->waitForText('My new media!', null, '.wall-entry');
        $I->click('Open Gallery', null, '.wall-entry');
        $I->waitForText('Gallery Test gallery', null, '#gallery-container .panel-heading');
    }

    private function dontseeMediaInStream(AcceptanceTester $I)
    {
        $I->click('Stream', '.layout-nav-container');
        $I->waitForElementVisible('#wallStream .wall-entry');
        $I->dontSee('My new media!', null, '.wall-entry');
    }

    private function deleteGalleryItem(AcceptanceTester $I)
    {
        $this->clickGalleryItemDropDown($I,'Delete');
        $I->waitForText('Confirm delete item', null, '#globalModalConfirm');
        $I->click('Confirm', '#globalModalConfirm');
    }

    private function clickGalleryItemDropDown(AcceptanceTester $I, $item)
    {
        $I->wait(2);
        $I->click('.gallery-list-entry .dropdown-toggle');
        $I->wait(1);
        $I->click($item, '.gallery-list-entry .dropdown');
    }

    private function deleteGallery(AcceptanceTester $I)
    {
        $I->waitForElementVisible(Locator::elementAt('.gallery-list-entry', 3));
        $this->deleteGalleryItem($I);
        $I->waitForElementNotVisible(Locator::elementAt('.gallery-list-entry', 3));
    }

    private function deleteMedia(AcceptanceTester $I)
    {
        $I->waitForElementVisible(Locator::elementAt('.gallery-list-entry', 2));
        $this->deleteGalleryItem($I);
        $I->waitForElementNotVisible(Locator::elementAt('.gallery-list-entry', 2));
    }

    public function testCreateAndDeleteMediaOnSpace(AcceptanceTester $I)
    {
        $this->enableSpaceModule($I);
        $this->seeGalleryInCotnainerNav($I);
        $this->accessGallery($I);
        $this->createGallery($I);
        $this->uploadMedia($I);
        $this->editMedia($I);
        $this->seeMediaInStream($I);
        $this->deleteMedia($I);
        $this->dontseeMediaInStream($I);
        $this->accessGallery($I);
        $this->deleteGallery($I);
    }

    public function testShowRelatedPostOnMedia(AcceptanceTester $I)
    {
        $this->enableSpaceModule($I);
        $this->seeGalleryInCotnainerNav($I);
        $this->accessGallery($I);
        $this->createGallery($I);
        $this->uploadMedia($I);
        $this->clickGalleryItemDropDown($I,'Show connected post');
        $I->wait(2);
        $I->waitForText('logo.jpg', null, '#wallStream');
    }

    public function testUploadInvalidFile(AcceptanceTester $I)
    {
        $this->enableSpaceModule($I);
        $this->seeGalleryInCotnainerNav($I);
        $this->accessGallery($I);
        $this->createGallery($I);
        $this->uploadMedia($I, 'test.txt', true);
        $I->seeError('Some files could not be uploaded');

    }

    /* public function testInstallAndCreatUserEntry(AcceptanceTester $I)
     {
         $I->amUser1();
         $I->wantToTest('the creation of a task list on user profile');
         $I->amGoingTo('install the calendar module on user profile');
         $this->enableProfileModule($I);

         $I->amOnProfile();
         $I->waitForText('Tasks', null, '.layout-nav-container');

         $I->amGoingTo('create a new task list');
         $I->click('Tasks', '.layout-nav-container');
         $I->waitForText('Task Lists');

         $I->click('Add Task List');
         $I->waitForText('Create task list', null, '#globalModal');
         $I->fillField('#tasklist-name', 'My New Tasklist');
         $I->click('Save', '#globalModal');

         $I->waitForText('My New Tasklist', null, '.task-list');

         $I->click(Locator::elementAt('[data-action-click="task.list.editTask"]',1));
         $I->waitForText('Create new task', null, '#globalModal');

         $I->fillField('Task[title]', 'My First Task');
         $I->fillField('#task-description .humhub-ui-richtext', 'This is a test task!');

         $I->click('Save', '#globalModal');
         $I->waitForText('My First Task', null,'.task-list-task-title-bar');

         $I->click('[data-task-id="1"]');
         $I->waitForText('This is a test task!');
         $I->see('Begin Task');
         $I->click('Begin Task');
         // Check for in progress badge
         $I->waitForElementVisible('.label-info .fa-edit', null,'[data-task-id="1"]');

         $I->click('[data-task-id="1"]');
         $I->waitForText('Finish Task');
         $I->click('Finish Task');

         $I->waitForText('My First Task', null,'.tasks-completed');
     }

     private function enableProfileModule(AcceptanceTester $I)
     {
         $I->amOnRoute(Url::toRoute(['/user/account/edit-modules', 'moduleId' => 'tasks']));
         $I->wait(1);
         $I->executeJS('$(\'.media-heading\').each(function() {if($(this).text() === \'Tasks\') {$(this).siblings(\'a:visible\').click()}});');
         $I->wait(2);
     }*/
}