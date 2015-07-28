<?php
class Phergie_Plugin_Games extends Phergie_Plugin_Abstract
{
    public function onLoad()
    {
        $this->getPluginHandler()->getPlugin('Command');
		$this->getPluginHandler()->getPlugin('Send');
		$this->getPluginHandler()->getPlugin('Permission');
		$this->getPluginHandler()->getPlugin('Formatting');
    }
    
    
    public function onCommandRr()
    {
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
		
		$gun = array(
		            "pistol",
		            "shotgun",
		            "AK-47",
		            "machine gun",
		            "sniper",
		            "P60",
		            "bazooka"
		            
		        );
		
		$responses = array(
		            "kicks $nick in the head",
		            "slaps $nick so hard, their head falls off",
		            "throws Chuck Norris at $nick... Goodbye",
		            "picks up a " . $gun[rand(0, count($gun) - 1)] . " and fires at $nick, killing them instantly",
		            "picks up a " . $gun[rand(0, count($gun) - 1)] . " and fires at $nick but misses",
		            "picks up a " . $gun[rand(0, count($gun) - 1)] . " and blows a hole $nick's chest",
		            "fills the " . $gun[rand(0, count($gun) - 1)] . " with all the ammo it can find, fires at $nick, watches them die",
		            "fires a " . $gun[rand(0, count($gun) - 1)] . " at $nick but the gun exploads and kills itself instead",
		            "looks around for its gun but can't find it anywhere"
		        );
        
        
        $this->doAction($source, $responses[rand(0, count($responses) - 1)]);
    }
    
    public function onCommandRoll($args)
    {
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
        
        $args = explode("d", strtolower($args));
        
        if (count($args) !== 2)
        {
            $this->plugins->send->send($source, "Incorrect amount of arguments, Example: " . $this->getConfig('prefix') . "roll 3d3", $nick);
        } else if (!is_numeric($args[0]))
        {
            $this->plugins->send->send($source, "Both values must be a number, Example: " . $this->getConfig('prefix') . "roll 3d3", $nick);
        } else if (!is_numeric($args[1]))
        {
            $this->plugins->send->send($source, "Both values must be a number, Example: " . $this->getConfig('prefix') . "roll 3d3", $nick);
        } else if ($args[0] > 100000)
        {
            $this->plugins->send->send($source, "I may have big hands but I can only roll up to 100,000 dice, Example: " . $this->getConfig('prefix') . "roll 3d3", $nick);
        } else if ($args[1] > 100000)
        {
            $this->plugins->send->send($source, "If a dice has more than 100,000 sides, isn't it just a ball? I can't count what side a ball lands on. Example: " . $this->getConfig('prefix') . "roll 3d3", $nick);
        } else if ($args[0] == 0)
        {
            $this->plugins->send->send($source, "You want me to throw no dice? Ok then, you got nothing. Example: " . $this->getConfig('prefix') . "roll 3d3", $nick);
        } else if ($args[1] == 0)
        {
            $this->plugins->send->send($source, "How can a dice have no sides? Please think about it, Example: " . $this->getConfig('prefix') . "roll 3d3", $nick);
        } else if ($args[0] < 0)
        {
            $this->plugins->send->send($source, "I think you are having counting problems, I can't throw a minus number of dice. Example: " . $this->getConfig('prefix') . "roll 3d3", $nick);
        } else if ($args[1] < 0)
        {
            $this->plugins->send->send($source, "I don't understand how to use inverted dice, Example: " . $this->getConfig('prefix') . "roll 3d3", $nick);
        } else {
            $roll = 0;
            
            $c = 1;
            while ($c !== $args[0] + 1)
            {
                $roll = $roll + rand(1, $args[1]);
                $c++;
            }
            
            if ($arg[0] = 1)
            {
                $this->plugins->send->send($source, $args[0] . " dice with " . $args[1] . " sides was rolled, the resulting total was: " . $roll, $nick);
            } else {
                $this->plugins->send->send($source, $args[0] . " die with " . $args[1] . " sides were each rolled, the resulting total was: " . $roll, $nick);
            }
        }
    }
}