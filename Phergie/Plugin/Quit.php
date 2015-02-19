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
 * @package   Phergie_Plugin_Quit
 * @author    Phergie Development Team <team@phergie.org>
 * @copyright 2008-2012 Phergie Development Team (http://phergie.org)
 * @license   http://phergie.org/license New BSD License
 * @link      http://pear.phergie.org/package/Phergie_Plugin_Quit
 */

/**
 * Terminates the current connection upon command.
 *
 * @category Phergie
 * @package  Phergie_Plugin_Quit
 * @author   Phergie Development Team <team@phergie.org>
 * @license  http://phergie.org/license New BSD License
 * @link     http://pear.phergie.org/package/Phergie_Plugin_Quit
 * @uses     Phergie_Plugin_Command pear.phergie.org
 */
class Phergie_Plugin_Quit extends Phergie_Plugin_Abstract
{
    /**
     * Checks for dependencies.
     *
     * @return void
     */
    public function onLoad()
    {
        $this->getPluginHandler()->getPlugin('Command');
		$this->getPluginHandler()->getPlugin('Send');
		$this->getPluginHandler()->getPlugin('Permission');
    }

    /**
     * Issues a quit command when a message is received requesting that the
     * bot terminate the current connection.
     *
     * @param string $message Optional message to emit when quitting
     *
     * @return void
     */
    public function onCommandQuit($message = null)
    {
    	$event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
    	
    	if ($this->plugins->permission->getLevel($hostmask) == 3)
    	{
        	if ($message === null) {
            	$message = 'Requested by ' . $this->getEvent()->getNick();
        		}
        	$this->doQuit($message);
    	} else {
    		$this->plugins->send->send($source, "You dont have the permission to do that :<", $nick);
    	}
    }
}
