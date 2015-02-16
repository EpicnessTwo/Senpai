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
}