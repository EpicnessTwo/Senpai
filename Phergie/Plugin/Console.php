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
			$data = preg_split('/[\r\n]+/', exec("git pull"), -1, PREG_SPLIT_NO_EMPTY);
		    $counter = 0;
		    while($counter !== count($data))
				{   
				    if($data[$counter] == "Already up-to-date.")
				    {
					    $this->plugins->send->send($source, "The source code is already at the latest version. No need to update!", $nick);
					    $counter++;
				    } else {
				    $this->plugins->send->send($source, "Updating source code and restarting the bot!", $nick);
				    time_nanosleep(2, 100000);
				    $this->doQuit("Source updated - Restart required!");
				    } 
				    
				}
    	} else {
    	    $this->plugins->send->send($source, $this->getConfig('error.noperms') , $nick);
    	}
    }
}