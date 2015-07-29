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
		$this->getPluginHandler()->getPlugin('Formatting');
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
    
    public function onCommandPart($channel = null, $reason = null)
    {
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
    	
    	if ($this->plugins->permission->getLevel($hostmask) >= 3)
    	{
    		if ($channel === null){$channel = $source;}
    		
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
    
    public function onInvite()
    {
    	$event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
    	$channel = $this->event->getArgument(1);
    	
    	if ($this->getConfig("owner.allowInvite"))
    	{
    		$this->doJoin($channel);
    	} else {
    		$this->plugins->send->send($source, "Sorry but I'm not allowed to join random channels. Ask my owner to take me there instead.", $nick);
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
			$args = $this->plugins->formatting->extraFormatting($args);
			$args = $this->plugins->formatting->format($args);
		    $this->plugins->send->send($source, $args, $nick);
		} else {
		    $this->plugins->send->send($source, $this->getConfig('error.noperms') , $nick);
		}
		
    }
    
    public function onCommandNotice($to, $args)
    {
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
		
		if ($this->plugins->permission->getLevel($hostmask) >= 2)
		{
			$args = $this->plugins->formatting->extraFormatting($args);
			$args = $this->plugins->formatting->format($args);
		    $this->plugins->send->notice($to, $args, $nick);
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
			$args = $this->plugins->formatting->extraFormatting($args);
			$args = $this->plugins->formatting->format($args);
		    $this->doAction($source, $args);
		} else {
		    $this->plugins->send->send($source, $this->getConfig('error.noperms') , $nick);
		}
    }
    
    public function onCommandRaw($args)
    {
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
		
		if ($this->plugins->permission->getLevel($hostmask) >= 3)
		{
			$args = $this->plugins->formatting->extraFormatting($args);
			$args = $this->plugins->formatting->format($args);
		    $this->doRaw($args);
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
			$args = $this->plugins->formatting->extraFormatting($args);
			$args = $this->plugins->formatting->format($args);
		    $this->plugins->send->send($to, $args, $nick);
		} else {
		    $this->plugins->send->send($source, $this->getConfig('error.noperms') , $nick);
		}
		
    }
    
}