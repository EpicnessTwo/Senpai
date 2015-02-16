<?php
class Phergie_Plugin_Send extends Phergie_Plugin_Abstract
{
	// This module is made to allow for outputs to contain the prefix
	// set in the settings file. If the value is not found, it will
	// simply default to nothing at all.
	//
	// For fun, you can use the following variables inside the config
	// item.
	//
	// £<xx> = Example: "£04" will set the command colour to red
	// &nick = Example: EpicKitty Command output
	// &rand = Just like using £ for specifying a colour, this will pick one randomly
	//
	// You can call this from another plugin by doing the following
	// $this->plugins->send->send($to, $content, $nick);
	//
	// $to = Where is the message being sent to? (A channel or a user)
	// $content = What is the message going to say?
	// $nick = The nickname of the person who called the command ($this->getEvents->getNick();)
	
	public function send($to, $content, $nick)
	{
		$prefix = $this->getConfig('output.prefix');
		
		$array1 = array("£", "&nick", "&rand");
		$array2 = array("\x03", $nick, "\x03" . rand(0, 15));
		
		$prefix = str_ireplace($array1, $array2, $prefix);
		
		$this->doRaw("PRIVMSG " . $to . " :" . $prefix . $content);
	}
	
	public function notice($to, $content, $nick)
	{
		$prefix = $this->getConfig('output.prefix');
		
		$array1 = array("£", "&nick", "&rand");
		$array2 = array("\x03", $nick, "\x03" . rand(0, 15));
		
		$prefix = str_ireplace($array1, $array2, $prefix);
		
		$this->doRaw("NOTICE " . $to . " :" . $prefix . $content);
	}

}