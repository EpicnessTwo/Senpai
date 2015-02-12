<?php
/**
 * Phergie
 *
 * PHP version 5
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://phergie.org/license
 *
 * @category  Phergie
 * @package   Phergie_Tests
 * @author    Phergie Development Team <team@phergie.org>
 * @copyright 2008-2012 Phergie Development Team (http://phergie.org)
 * @license   http://phergie.org/license New BSD License
 * @link      http://pear.phergie.org/package/Phergie_Tests
 */

/**
 * Base test case class for Phergie tests that require access to mock
 * instances of one or more Phergie components.
 *
 * @category Phergie
 * @package  Phergie_Tests
 * @author   Phergie Development Team <team@phergie.org>
 * @license  http://phergie.org/license New BSD License
 * @link     http://pear.phergie.org/package/Phergie_Tests
 */
abstract class Phergie_TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Mock configuration
     *
     * @var Phergie_Config
     */
    protected $config;

    /**
     * Associative array for configuration setting values, accessed by the
     * mock configuration object using a callback
     *
     * @var array
     */
    protected $settings = array();

    /**
     * Mock connection
     *
     * @var Phergie_Connection
     */
    protected $connection;

    /**
     * Mock connection handler
     *
     * @var Phergie_Connection_Handler
     */
    protected $connections;

    /**
     * Mock event handler
     *
     * @var Phergie_Event_Handler
     */
    protected $events;

    /**
     * Mock plugin handler
     *
     * @var Phergie_Plugin_Handler
     */
    protected $plugins;

    /**
     * Mock end-user interface
     *
     * @var Phergie_Ui_Abstract
     */
    protected $ui;

    /**
     * Mock event processor
     *
     * @var Phergie_Process_Abstract
     */
    protected $processor;

    /**
     * User nick used in any events requiring one
     *
     * @var string
     */
    protected $nick = 'nick';

    /**
     * Event source used in any events requiring one
     *
     * @var string
     */
    protected $source = '#channel';

    /**
     * Destroys all initialized instance properties.
     *
     * @return void
     */
    public function tearDown()
    {
        unset(
            $this->plugins,
            $this->events,
            $this->connection,
            $this->config
        );
    }

    /**
     * Returns a mock configuration object.
     *
     * @return Phergie_Config
     */
    protected function getMockConfig()
    {
        if (empty($this->config)) {
            $this->config = $this->getMock(
                'Phergie_Config', array('offsetExists', 'offsetGet')
            );
            $this->config
                ->expects($this->any())
                ->method('offsetExists')
                ->will($this->returnCallback(array($this, 'configOffsetExists')));
            $this->config
                ->expects($this->any())
                ->method('offsetGet')
                ->will($this->returnCallback(array($this, 'configOffsetGet')));
        }
        return $this->config;
    }

    /**
     * Returns whether a specific configuration setting has a value. Only
     * intended for use by this class, but must be public for PHPUnit to
     * call them.
     *
     * @param string $name Name of the setting
     *
     * @return boolean TRUE if the setting has a value, FALSE otherwise
     */
    public function configOffsetExists($name)
    {
        return isset($this->settings[$name]);
    }

    /**
     * Returns the value of a specific configuration setting. Only intended
     * for use by this class, but must be public for PHPUnit to call them.
     *
     * @param string $name Name of the setting
     *
     * @return mixed Value of the setting
     */
    public function configOffsetGet($name)
    {
        return $this->settings[$name];
    }

    /**
     * Returns a mock connection object.
     *
     * @return Phergie_Connection
     */
    protected function getMockConnection()
    {
        if (empty($this->connection)) {
            $this->connection = $this->getMock('Phergie_Connection');
            $this->connection
                ->expects($this->any())
                ->method('getNick')
                ->will($this->returnValue($this->nick));
        }
        return $this->connection;
    }

    /**
     * Returns a mock connection handler object.
     *
     * @return Phergie_Connection_Handler
     */
    protected function getMockConnectionHandler()
    {
        if (empty($this->connections)) {
            $this->connections = $this->getMock('Phergie_Connection_Handler');
        }
        return $this->connections;
    }

    /**
     * Returns a mock event handler object.
     *
     * @return Phergie_Event_Handler
     */
    protected function getMockEventHandler()
    {
        if (empty($this->events)) {
            $this->events = $this->getMock('Phergie_Event_Handler');
        }
        return $this->events;
    }

    /**
     * Returns a mock plugin handler object.
     *
     * @return Phergie_Plugin_Handler
     */
    protected function getMockPluginHandler()
    {
        if (empty($this->plugins)) {
            $config = $this->getMockConfig();
            $events = $this->getMockEventHandler();
            $this->plugins = $this->getMock(
                'Phergie_Plugin_Handler',
                array(),
                array($config, $events)
            );
        }
        return $this->plugins;
    }

    /**
     * Returns a mock event object.
     *
     * @param array  $args   Optional associative array of event arguments
     * @param string $nick   Optional user nick to associate with the event
     * @param string $source Optional user nick or channel name to associate
     *        with the event as its source
     *
     * @return Phergie_Event_Request
     */
    protected function getMockEvent(array $args = array(),
        $nick = null, $source = null
    ) {
        $methods = array('getNick', 'getSource');
        foreach (array_keys($args) as $arg) {
            if (is_int($arg) || ctype_digit($arg)) {
                $methods[] = 'getArgument';
            } else {
                $methods[] = 'get' . ucfirst($arg);
            }
        }

        $event = $this->getMock(
            'Phergie_Event_Request',
            $methods
        );

        $nick = $nick ? $nick : $this->nick;
        $event
            ->expects($this->any())
            ->method('getNick')
            ->will($this->returnValue($nick));

        $source = $source ? $source : $this->source;
        $event
            ->expects($this->any())
            ->method('getSource')
            ->will($this->returnValue($source));

        foreach ($args as $key => $value) {
            if (is_int($key) || ctype_digit($key)) {
                $event
                    ->expects($this->any())
                    ->method('getArgument')
                    ->with($key)
                    ->will($this->returnValue($value));
            } else {
                $event
                    ->expects($this->any())
                    ->method('get' . ucfirst($key))
                    ->will($this->returnValue($value));
            }
        }

        return $event;
    }

    /**
     * Returns a mock end-user interface instance.
     *
     * @return Phergie_Ui_Abstract
     */
    protected function getMockUi()
    {
        if (empty($this->ui)) {
            $this->ui = $this->getMock(
                'Phergie_Ui_Abstract',
                array()
            );
        }
        return $this->ui;
    }

    /**
     * Returns a mock event processor instance.
     *
     * @param Phergie_Bot $bot Bot instance for which events are to be
     *        processed
     *
     * @return Phergie_Process_Abstract
     */
    protected function getMockProcessor(Phergie_Bot $bot)
    {
        if (empty($this->processor)) {
            if (!class_exists('Phergie_Process_Mock', false)) {
                $this->processor = $this->getMock(
                    'Phergie_Process_Abstract',
                    array(),
                    array($bot),
                    'Phergie_Process_Mock'
                );
            } else {
                $this->processor = new Phergie_Process_Mock($bot);
            }
        }
        return $this->processor;
    }

    /**
     * Sets the value of a configuration setting.
     *
     * @param string $setting Name of the setting
     * @param mixed  $value   Value for the setting
     *
     * @return void
     */
    protected function setConfig($setting, $value)
    {
        $this->settings[$setting] = $value;
    }
}
