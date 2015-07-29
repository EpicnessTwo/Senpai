<?php
class Phergie_Plugin_Random extends Phergie_Plugin_Abstract
{
    public function onLoad()
    {
        $this->getPluginHandler()->getPlugin('Command');
		$this->getPluginHandler()->getPlugin('Send');
		$this->getPluginHandler()->getPlugin('Permission');
		$this->getPluginHandler()->getPlugin('Formatting');
    }
    
    public function onCommandRandom($args = null)
    {
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
		
		if ($args === null){$args = 10;}
		
		if (!is_numeric($args))
		{
		    $this->plugins->send->send($source, "The argument must be a number!", $nick);
		} 
		else if ($args >= 40)
		{
		    $this->plugins->send->send($source, "The argument must be a under 40!", $nick);
		}
		else
		{
		    $array = array_merge(range("a", "z"), range("A", "Z"), range(0, 9));
		
		    $c = 0;
		    while ($c !== $args)
		    {
		        $out .= $array[rand(0, count($array) - 1)];
		        $c ++;
		    }
		    
		    $this->plugins->send->send($source, $out, $nick);
		}
    }
}