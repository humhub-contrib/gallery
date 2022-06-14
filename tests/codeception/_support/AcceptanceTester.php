<?php
namespace gallery;

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

    public function enableSpaceModule()
    {
        $this->amAdmin();
        $this->wantToTest('the creation of a task list');
        $this->amGoingTo('install the calendar module for space 1');
        $this->enableModule(1, 'gallery');
        $this->amUser2(true);
        $this->amOnSpace1();
    }
}