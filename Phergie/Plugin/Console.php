<?php
class Phergie_Plugin_Console extends Phergie_Plugin_Abstract
{
    public function onLoad()
    {
        $this->getPluginHandler()->getPlugin('Command');
		$this->getPluginHandler()->getPlugin('Send');
		$this->getPluginHandler()->getPlugin('Permission');
    }
    
    public function onCommandCmd($args)
    {
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
		
		if ($this->plugins->permission->getLevel($hostmask) >= 3)
    	{
			$data = preg_split('/[\r\n]+/', shell_exec($args), -1, PREG_SPLIT_NO_EMPTY);
		    $counter = 0;
		    while($counter !== count($data))
				{
					$this->plugins->send->send($source, $data[$counter], $nick);
					$counter++;
				}
    	} else {
    	    $this->plugins->send->send($source, $this->getConfig('error.noperms') , $nick);
    	}
    }
    
    public function onCommandEval($args)
    {
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
		
		if ($this->plugins->permission->getLevel($hostmask) >= 3)
    	{
			$data = preg_split('/[\r\n]+/', eval($args), -1, PREG_SPLIT_NO_EMPTY);
		    $counter = 0;
		    while($counter !== count($data))
				{
					$this->plugins->send->send($source, $data[$counter], $nick);
					$counter++;
				}
    	} else {
    	    $this->plugins->send->send($source, $this->getConfig('error.noperms') , $nick);
    	}
    }
    
    public function onCommandPush($args)
    {
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
		
		if ($this->plugins->permission->getLevel($hostmask) >= 3)
    	{
			exec("git add *");
			exec("git commit -m '$args'");
			exec("git push");
			$this->plugins->send->send($source, "Pushed to the master repo!", $nick);
    	} else {
    	    $this->plugins->send->send($source, $this->getConfig('error.noperms') , $nick);
    	}
    }
    
    public function onCommandUpdate()
    {
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
		
		if ($this->plugins->permission->getLevel($hostmask) >= 3)
    	{
			$base = exec("git merge-base @ @{u}");
			$local = exec("git rev-parse @{0}");
			$remote = exec("git rev-parse @{u}");
			
			$out = null;
			
			if($local == $remote)
			{
				$out = 2;
			} else if ($local == $base)
			{
				$out = 1;
			}
			
			if ($out === null)
			{
				$this->plugins->send->send($source, "Unknown error! Try using the 'check' command!", $nick);
			} else if ($out = 1){
				$this->plugins->send->send($source, "No need to update, the local copy is fine!", $nick);
			} else {
				$this->plugins->send->send($source, "Yep, looks like the source code is outdated. Going to update it now for you!", $nick);
				exec("git pull");
				$this->plugins->send->send($source, "Right, the source is updated. See you on the other side!", $nick);
				$this->doQuit("Updated source code - Restart required!");
			}
			
    	} else {
    	    $this->plugins->send->send($source, $this->getConfig('error.noperms') , $nick);
    	}
    }
    
    public function onCommandCheck()
    {
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
		
		if ($this->plugins->permission->getLevel($hostmask) >= 3)
    	{
			$base = exec("git merge-base @ @{u}");
			$local = exec("git rev-parse @{0}");
			$remote = exec("git rev-parse @{u}");
			
			if($local == $remote)
			{
				$out = "Up to date";
			} else if ($local == $base)
			{
				$out = "Needs updating";
			} else if ($remote == $base)
			{
				$out = "Local copy is modified";
			} else {
				$out = "Output unknown, can't detect git repo";
			}
			
			$this->plugins->send->send($source, "Current Git Status: " . $out, $nick);
    	} else {
    	    $this->plugins->send->send($source, $this->getConfig('error.noperms') , $nick);
    	}
    }
}