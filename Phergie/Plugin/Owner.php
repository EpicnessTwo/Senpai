<?php
class Phergie_Plugin_Owner extends Phergie_Plugin_Abstract
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
    
    public function onCommandJoin($channel, $key = null)
    {
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
    	
    	if ($this->plugins->permission->getLevel($hostmask) >= 3)
    	{
    	       if ($key === null)
    	       {
    	           $this->doJoin($channel);
    	       } else {
    	           $this->doJoin($channel, $key);
    	       }
    	} else {
    	    $this->plugins->send->send($source, $this->getConfig('error.noperms') , $nick);
    	}
    }
    
    public function onCommandPart($channel, $reason = null)
    {
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
    	
    	if ($this->plugins->permission->getLevel($hostmask) >= 3)
    	{
    	       if ($reason === null)
    	       {
    	           $this->doRaw("PART " . $channel . " :Removed from the channel by $nick");
    	       } else {
    	           $this->doRaw("PART " . $channel . " :" . $reason);
    	       }
    	} else {
    	    $this->plugins->send->send($source, $this->getConfig('error.noperms') , $nick);
    	}
    }
    
    public function onCommandSay($args)
    {
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
		
		if ($this->plugins->permission->getLevel($hostmask) >= 2)
		{
		    $this->plugins->send->send($source, $args, $nick);
		} else {
		    $this->plugins->send->send($source, $this->getConfig('error.noperms') , $nick);
		}
		
    }
    
    public function onCommandAct($args)
    {
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
		
		if ($this->plugins->permission->getLevel($hostmask) >= 2)
		{
		    $this->doAction($source, $args);
		} else {
		    $this->plugins->send->send($source, $this->getConfig('error.noperms') , $nick);
		}
    }
    
    public function onCommandMsg($to, $args)
    {
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
		
		if ($this->plugins->permission->getLevel($hostmask) >= 3)
		{
		    $this->plugins->send->send($to, $args, $nick);
		} else {
		    $this->plugins->send->send($source, $this->getConfig('error.noperms') , $nick);
		}
		
    }
    
}