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
        
            // Pre-Fun things
            // This removes the https:// and http:// from the front of the $args
            // just incase the user left them there
            
            $args = str_ireplace(array("http://", "https://"), array("", ""), $args);
        
        
        	// IPv6 Handling
        	// This detects if the input args is an IPv6 address and runs if
        	// it is.\x03
        if (filter_var($args , FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
        {
            if ($this->getConfig('commands.fancyOutput'))
            {
                $RDNS = "\x0304\x02Reverse DNS: \x0F";
            } else {
                $RDNS = "Reverse DNS: ";
            }
            
            $data = shell_exec("host " . escapeshellcmd($args));
	    	
	    	$data = explode(" ", $data);
	    	
	    	if ($data[0] == "Host")
	    	{
	    	    if ($this->getConfig('commands.fancyOutput'))
                {
	    	        $this->plugins->send->send($source, "\x02\x0304No RDNS found for $args", $nick);
                } else {
                    $this->plugins->send->send($source, "No RDNS found for $args", $nick);
                }
	    	} else {
	    		$this->plugins->send->send($source, $RDNS . rtrim($data[4], "."), $nick);
	    	}
            
        } else 
        
        	// IPv4 Handling
        	// This detects if the input args is an IPv4 address and runs if
        	// it is.
        if (filter_var($args , FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
        {
            if ($this->getConfig('commands.fancyOutput'))
            {
                $RDNS = "\x0304\x02Reverse DNS: \x0F";
            } else {
                $RDNS = "Reverse DNS: ";
            }
            
            $data = shell_exec("host " . escapeshellcmd($args));
	    	
	    	$data = explode(" ", $data);
	    	
	    	if ($data[0] == "Host")
	    	{
	    	    if ($this->getConfig('commands.fancyOutput'))
                {
	    	        $this->plugins->send->send($source, "\x02\x0304No RDNS found for $args", $nick);
                } else {
                    $this->plugins->send->send($source, "No RDNS found for $args", $nick);
                }
	    	} else {
	    		$this->plugins->send->send($source, $RDNS . rtrim($data[4], "."), $nick);
	    	}
        } else
        
        	// Hostmask Handling
        	// This detects if the input args is a hostmask and runs if
        	// it is.
        	
        
        if (
        	preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $args)
            && preg_match("/^.{1,253}$/", $args)
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $args)
            )
        {
            
                // Used for the fancy output option. If it is set in the options, it will
                // make the output look a little nicer.
            
            if ($this->getConfig('commands.fancyOutput'))
            {
                $IPV4 = "\x0304\x02IPv4: \x0F\x0305";
                $IPV6 = "\x0304\x02IPv6: \x0F\x0305";
                $MX = "\x0304\x02MX: \x0F\x0305";
            } else {
                $IPV4 = "IPv4: ";
                $IPV6 = "IPv6: ";
                $MX = "MX: ";
            }
	    	    // The following 2 lines runs the command "host", from a terminal and uses
	    	    // the output to generate the content of the command
	    	$data = shell_exec("host " . escapeshellcmd($args));
	    	$data = preg_split('/[\r\n]+/', $data, -1, PREG_SPLIT_NO_EMPTY);
	    	
	    	    // Just setting the variables as arrays so that "array_push()" works when
	    	    // selecting where to put each line of data.
	    	$IPV4_Array = array();
	    	$IPV6_Array = array();
	    	$MX_Array = array();
	    	
	    	    // These are set to true once something triggers them. This trigger being what
	    	    // tells the output what types of records are present. No point outputing the "MX: "
	    	    // for example if the domain queried doesn't have any.
	    	$IPV4_Trigger = false;
	    	$IPV6_Trigger = false;
	    	$MX_Trigger = false;
	    	
	    	    // Runs though for every line outputted from the command
	    	foreach ($data as $item)
	    	{
	    	        // IPv4 Detection
	    	    if (strpos($item, " has address "))
	    	    {
	    	        $var = str_ireplace($args . " has address ", "", $item);
	    	        array_push($IPV4_Array, $var);
	    	        $IPV4_Trigger = true;
	    	    } else
	    	        // IPv6 Detection
	    	    if (strpos($item, " has IPv6 address "))
	    	    {
	    	        $var = str_ireplace($args . " has IPv6 address ", "", $item);
	    	        array_push($IPV6_Array, $var);
	    	        $IPV6_Trigger = true;
	    	    } else
	    	        // MX Detection
	    	    if (strpos($item, " mail is handled by "))
	    	    {
	    	        $var = str_ireplace($args . " mail is handled by ", "", $item);
	    	        $var = explode(" ", $var);
	    	        array_push($MX_Array, $var[1]);
	    	        $MX_Trigger = true;
	    	    }
	    	}
	    	
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
            
            if (!$IPV4_Trigger && !$IPV6_Trigger && !$MX_Trigger)
            {
                if ($this->getConfig('commands.fancyOutput'))
                {
            	    $this->plugins->send->send($source, "\x02\x0304No records found for this domain", $nick);
                } else {
                    $this->plugins->send->send($source, "No records found for this domain", $nick);
                }
            }
        } else {
                // It will only get here if it fails the IPv4, IPv6 and Hostname checks. This is just some
                // extra security and also doubles up as a way of stopping people including variables for
                // for the host command in their arguments.
            if ($this->getConfig('commands.fancyOutput'))
            {
                $this->plugins->send->send($source, "\x02\x0304This is not a valid IPv4, IPv6 or Hostname", $nick);
            } else {
                $this->plugins->send->send($source, "This is not a valid IPv4, IPv6 or Hostname", $nick);
            }
        }    
    }
}