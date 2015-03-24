<?php
class Phergie_Plugin_Utilities extends Phergie_Plugin_Abstract
{
    
    public function onLoad()
    {
        $this->getPluginHandler()->getPlugin('Command');
		$this->getPluginHandler()->getPlugin('Send');
		$this->getPluginHandler()->getPlugin('Permission');
		$this->getPluginHandler()->getPlugin('Formatting');
    }
    
    public function onCommandHost($args)
    {
        
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
        
        // Used for the fancy output option. If it is set in the options, it will
        // make the output look a little nicer.
        
        if ($this->getConfig('commands.fancyOutput'))
        {
            $IPV4 = "\x0304\x02IPv4: \x0F";
            $IPV6 = "\x0304\x02IPv6: \x0F";
            $MX = "\x0304\x02MX: \x0F";
        } else {
            $IPV4 = "IPv4: ";
            $IPV6 = "IPv6: ";
            $MX = "MX: ";
        }
		    	
		$data = shell_exec("host " . escapeshellcmd($args));
		$data = preg_split('/[\r\n]+/', $data, -1, PREG_SPLIT_NO_EMPTY);
		
		$IPV4_Array = array();
		$IPV6_Array = array();
		$MX_Array = array();
		
		$IPV4_Trigger = false;
		$IPV6_Trigger = false;
		$MX_Trigger = false;
		
		foreach ($data as $item)
		{
		    if (strpos($item, " has address "))
		    {
		        $var = str_ireplace($args . " has address ", "", $item);
		        array_push($IPV4_Array, $var);
		        $IPV4_Trigger = true;
		    } else
		    if (strpos($item, " has IPv6 address "))
		    {
		        $var = str_ireplace($args . " has IPv6 address ", "", $item);
		        array_push($IPV6_Array, $var);
		        $IPV6_Trigger = true;
		    } else
		    if (strpos($item, " mail is handled by "))
		    {
		        $var = str_ireplace($args . " mail is handled by ", "", $item);
		        $var = explode(" ", $var);
		        array_push($MX_Array, $var[1]);
		        $MX_Trigger = true;
		    }
		}
		
		//Add the outputs! use implode(" - ", $yep!)
        if ($IPV4_Trigger)
        {
        	$this->plugins->send->send($source, $IPV4 . implode(" - ", $IPV4_Array), $nick);
        }
        if ($IPV6_Trigger)
        {
        	$this->plugins->send->send($source, $IPV6 . implode(" - ", $IPV6_Array), $nick);
        }
        if ($MX_Trigger)
        {
        	$this->plugins->send->send($source, $MX . implode(" - ", $MX_Array), $nick);
        }
        
    }
}