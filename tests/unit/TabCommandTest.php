<?php

/**
 * Testing the tab command.
 *
 * PHP version 5
 *
 * @category  Testing
 * @package   Toxic
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2014-2015 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Toxic_XH
 */

require_once '../../cmsimple/functions.php';
require_once './classes/TabCommand.php';

/**
 * Testing the tab command.
 *
 * @category Testing
 * @package  Toxic
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Toxic_XH
 */
class TabCommandTest extends PHPUnit_Framework_TestCase
{
    /**
     * The subject under test.
     *
     * @var Toxic_TabCommand
     */
    protected $subject;

    /**
     * Sets up the test fixture.
     *
     * @return void
     *
     * @global string The script name.
     * @global string The selected URL.
     * @global array  The localization of the plugins.
     */
    public function setUp()
    {
        global $sn, $su, $plugin_tx;

        $sn = '/xh/';
        $su = 'Welcome';
        $plugin_tx = array(
            'toxic' => array(
                'label_class' => 'Class',
                'label_save' => 'Save'
            )
        );
        $pageData = array('toxic_class' => 'test');
        $this->subject = new Toxic_TabCommand($pageData);
    }

    /**
     * Tests that a form element is rendered.
     *
     * @return void
     */
    public function testRendersForm()
    {
        $this->assertRenders(
            array(
                'tag' => 'form',
                'id' => 'toxic_tab',
                'attributes' => array(
                    'action' => '/xh/?Welcome',
                    'method' => 'post'
                )
            )
        );
    }

    /**
     * Tests that the class input is rendered.
     *
     * @return void
     *
     * @global array The configuration of the plugins.
     */
    public function testRendersClassInput()
    {
        global $plugin_cf;

        $plugin_cf['toxic']['classes_available'] = '';
        $this->assertRenders(
            array(
                'tag' => 'input',
                'attributes' => array(
                    'type' => 'text',
                    'name' => 'toxic_class',
                    'value' => 'test'
                ),
                'parent' => array(
                    'tag' => 'label',
                    'content' => 'Class'
                ),
                'ancestor' => array('tag' => 'form')
            )
        );
    }

    /**
     * Tests that the class select element is rendered.
     *
     * @return void
     *
     * @global array The configuration of the plugins.
     */
    public function testRendersClassSelect()
    {
        global $plugin_cf;

        $plugin_cf['toxic']['classes_available'] = 'one,two,three,test';
        $this->assertRenders(
            array(
                'tag' => 'select',
                'attributes' => array(
                    'name' => 'toxic_class'
                ),
                'children' => array(
                    'count' => 5,
                    'only' => array('tag' => 'option')
                ),
                'child' => array(
                    'tag' => 'option',
                    'content' => 'test',
                    'attributes' => array('selected' => 'selected')
                ),
                'parent' => array(
                    'tag' => 'label',
                    'content' => 'Class'
                ),
                'ancestor' => array('tag' => 'form')
            )
        );
    }

    /**
     * Tests that a submit button is rendered.
     *
     * @return void
     */
    public function testRendersSubmitButton()
    {
        $this->assertRenders(
            array(
                'tag' => 'button',
                'attributes' => array('name' => 'save_page_data'),
                'content' => 'Save',
                'parent' => array(
                    'tag' => 'p',
                    'attributes' => array('class' => 'toxic_tab_buttons')
                ),
                'ancestor' => array('tag' => 'form')
            )
        );
    }

    /**
     * Asserts that $matcher is rendered.
     *
     * @param array $matcher A matcher.
     *
     * @return void
     */
    protected function assertRenders($matcher)
    {
        @$this->assertTag($matcher, $this->subject->render());
    }
}

?>
