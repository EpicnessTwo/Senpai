<?php
class Phergie_Plugin_General extends Phergie_Plugin_Abstract
{
    
    public function onLoad()
    {
        $this->getPluginHandler()->getPlugin('Command');
		$this->getPluginHandler()->getPlugin('Send');
		$this->getPluginHandler()->getPlugin('Permission');
    }
    
    
    public function onCommandWhoami()
    {
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
		
		if ($this->plugins->permission->isOwner($hostmask)){ $out = "my Master!";} else
		if ($this->plugins->permission->isAdmin($hostmask)){ $out = "an Admin!";} else
		if ($this->plugins->permission->isBlacklisted($hostmask)){ $out = "banned from using me! What did you do wrong? ;~;";} else
		{ $out = "... no wait... I have no idea who you are! You can be my friend now :3!!";}
		
		$this->plugins->send->send($source, "Well it seams that you are " . $out, $nick);
    }
    
    public function onCommandTime()
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
		    // Thanks Cajs for some help with the data() function!
		    $this->plugins->send->send($source, date('l jS \of F Y h:i:s A'), $nick);
		}
    }
    
    public function onCommandSource()
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
		    $this->plugins->send->send($source, "Here is my source code! https://github.com/EpicnessTwo/Senpai", $nick);
		}
    }
    
    public function onCommandAbout()
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
		    $this->plugins->send->send($source, $this->getConfig('bot.version'), $nick);
		}
    }
    
}