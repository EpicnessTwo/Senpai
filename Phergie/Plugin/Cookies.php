<?php
class Phergie_Plugin_Cookies extends Phergie_Plugin_Abstract
{
   /* This plugin is the first of the fun plugins im planning to add to this bot!
    * 
    * Who doesn't like cookies these days? This plugin will output a random cookie
    * type and a snack to gon with it :D
    */
    
    public function onLoad()
    {
        $this->getPluginHandler()->getPlugin('Command');
		$this->getPluginHandler()->getPlugin('Send');
		$this->getPluginHandler()->getPlugin('Permission');
    }
    
    public function onCommandCookie($who = null)
    {
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
        
        if($this->plugins->permission->isBlacklisted($hostmask))
		{
		    $this->plugins->send->send($source, $this->getConfig('error.blacklisted') , $nick);
		} else {
		    $cookies = array(
		            "some freshly baked",
		            "some chocolate",
		            "a load of",
		            "a huge",
		            "some magical",
		            "some awesome",
		            "some tasty",
		            "some walm",
		            "some old",
		            "some fresh"
		        );
		    $countcookies = count($cookies);      
		  
		        
		    $extra = array(
		            "a walm glass of milk",
		            "a nice cup of tea",
		            "some more cookies",
		            "a walm, cosy hug",
		            "a fresh glass of orange juice",
		            "a mug of hot chocolate",
		        );
		    $countextra = count($extra); 
		       
		    if($who === null){$who = $nick;} //If the $who variable isnt set, set it to the nick of the person who ran the command!
		    
		    $this->doAction($source, "gives " . $cookies[rand(0, $countcookies)] . " cookies to " . $nick . " with " . $extra[rand(0, $countextra)]. ".");
		}
    }
}