<?php

/**
 * Testing the tab command.
 *
 * PHP version 5
 *
 * @category  Testing
 * @package   Toxic
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2014 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
 * @link      http://3-magi.net/?CMSimple_XH/Toxic_XH
 */

require_once '../../cmsimple/functions.php';
require_once './classes/Presentation.php';

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
    private $_subject;

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
                'label_classes' => 'Classes',
                'label_save' => 'Save'
            )
        );
        $pageData = array('toxic_classes' => 'test');
        $this->_subject = new Toxic_TabCommand($pageData);
    }

    /**
     * Tests that a form element is rendered.
     *
     * @return void
     */
    public function testRendersForm()
    {
        $this->_assertRenders(
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
     * Tests that a classes field is rendered.
     *
     * @return void
     */
    public function testRendersClassesField()
    {
        $this->_assertRenders(
            array(
                'tag' => 'input',
                'attributes' => array(
                    'type' => 'text',
                    'name' => 'toxic_classes',
                    'value' => 'test'
                ),
                'parent' => array(
                    'tag' => 'label',
                    'content' => 'Classes'
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
        $this->_assertRenders(
            array(
                'tag' => 'button',
                'attributes' => array('name' => 'save_page_data'),
                'content' => 'Save',
                'parent' => array(
                    'tag' => 'div',
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
    private function _assertRenders($matcher)
    {
        $this->assertTag($matcher, $this->_subject->render());
    }
}

?>
