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
    
    
    /*
     * This is the host command for the bot and so far, it's unbreakable!
     * I have spent many hours coding this command and there for, it supports
     * hostname, IPv4 and IPv6 inputs.
     *
     * With the hostname input, it will return all the IPv4, IPv6 and MX records
     * that are attached to the hostname using a DNS lookup. This command will
     * only work on Linux however due to windows not having a "host" command.
     * Maybe in the future, I might attempt to port this to windows but it's
     * highly doubtful as it has been built to run on linux and since anyone can
     * install or get a linux server for cheep or free anyway.
     *
     * With the IPv4 and IPv6 input, it returns the RDNS address if any. The command
     * has been setup to ensure any error is explained simply and displayed in the
     * channel that the command was run in.
     */
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
        	// it is.
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
	    	        $var = explode(" ", $item);
	    	        array_push($IPV4_Array, $var[3]);
	    	        $IPV4_Trigger = true;
	    	    } else
	    	        // IPv6 Detection
	    	    if (strpos($item, " has IPv6 address "))
	    	    {
	    	        $var = explode(" ", $item);
	    	        array_push($IPV6_Array, $var[4]);
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
    
    /*
     * This is the ping command and it's still WIP (Work in progress)!
     * Please use this command wisely as it may still contain bugs or exploits that I
     * am yet to find and patch.
     *
     * P.S. I need to write more about this command once I've finished it!
     */
    public function onCommandPing($args)
    {
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
		
		// Variables
		
		    // Setting the following lines to true will disable them. This is in case
		    // the server that is running the bot does not support IPv4 or IPv6.
		    // Error handling may not be implemented in further down so do make
		    // sure this is configured correctly!
		$noIPv4 = false;
		$noIPv6 = false;
		
		    // How many times do you want the ping command to cycle? (Default: 4)
		$pingCount = 4;
		
		    // Pre-Fun things
            // This removes the https:// and http:// from the front of the $args
            // just incase the user left them there
            
            $args = str_ireplace(array("http://", "https://"), array("", ""), $args);
        
        
        	// IPv4 Handling
        	// This detects if the input args is an IPv4 address and runs if
        	// it is.
        if (filter_var($args , FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
        {
            //IPv4
            
                // Executes the "ping" command and stores the data in the array "$data"
                // The data is split at newlines
            $data = shell_exec("ping -c $pingCount -v -q " . escapeshellcmd($args));
	    	$data = preg_split('/[\r\n]+/', $data, -1, PREG_SPLIT_NO_EMPTY);
	    	
	    	    // Grabs the IP Address from the first line
	    	$ip = explode(" ", $data[0]);
	    	$ip = $ip[2];
	    	
	    	    // Grabs the total time taken from the 3rd line
	    	$total = explode(" ", $data[2]);
	    	$total_count = count($total) - 1;
	    	$total = str_ireplace("ms", "", $total[$total_count]);
	    	
	    	    // Grabs the average from the final line
	    	$average = explode(" ", $data[3]);
	    	$average = explode("/", $average[3]);
	    	$average = $average[1];
	    	
	    	if ($this->getConfig('commands.fancyOutput'))
            {
                $this->plugins->send->send($source, "\x02\x0304Pinged \x0305$args $ip \x0304a total of \x0305$pingCount \x0304times. Total time: \x0305$total \x0304ms. Average: \x0305$average \x0304ms.", $nick);
            } else {
                $this->plugins->send->send($source, "Pinged $args $ip a total of $pingCount times. Total time: $total ms. Average: $average ms.", $nick);
            }
            
        } else
        
        	// Hostmask Handling
        	// This detects if the input args is a hostmask and runs if
        	// it is.
        if (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $args) && preg_match("/^.{1,253}$/", $args) && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $args))
        {
            //Hostmask
                // Executes the "ping" command and stores the data in the array "$data"
                // The data is split at newlines
            $data = shell_exec("ping -c $pingCount -v -q " . escapeshellcmd($args));
	    	$data = preg_split('/[\r\n]+/', $data, -1, PREG_SPLIT_NO_EMPTY);
	    	
	    	    // Grabs the IP Address from the first line
	    	$ip = explode(" ", $data[0]);
	    	$ip = $ip[2];
	    	
	    	    // Grabs the total time taken from the 3rd line
	    	$total = explode(" ", $data[2]);
	    	$total_count = count($total) - 1;
	    	$total = str_ireplace("ms", "", $total[$total_count]);
	    	
	    	    // Grabs the average from the final line
	    	$average = explode(" ", $data[3]);
	    	$average = explode("/", $average[3]);
	    	$average = $average[1];
	    	
	    	if ($this->getConfig('commands.fancyOutput'))
            {
                $this->plugins->send->send($source, "\x02\x0304Pinged \x0305$args $ip \x0304a total of \x0305$pingCount \x0304times. Total time: \x0305$total \x0304ms. Average: \x0305$average \x0304ms.", $nick);
            } else {
                $this->plugins->send->send($source, "Pinged $args $ip a total of $pingCount times. Total time: $total ms. Average: $average ms.", $nick);
            }
            
        } else {
                // It will only get here if it fails the IPv4, IPv6 and Hostname checks. This is just some
                // extra security and also doubles up as a way of stopping people including variables for
                // for the host command in their arguments.
            if ($this->getConfig('commands.fancyOutput'))
            {
                $this->plugins->send->send($source, "\x02\x0304This is not a valid IPv4 or Hostname", $nick);
            } else {
                $this->plugins->send->send($source, "This is not a valid IPv4 or Hostname", $nick);
            }
        } 
    }
    
        /*
     * This is the ping command and it's still WIP (Work in progress)!
     * Please use this command wisely as it may still contain bugs or exploits that I
     * am yet to find and patch.
     *
     * P.S. I need to write more about this command once I've finished it!
     */
    public function onCommandPing6($args)
    {
        $event = $this->getEvent();
		$source = $event->getSource();
		$nick = $event->getNick();
		$hostmask = explode("!", $this->event->getHostmask());
		$hostmask = $hostmask[1];
		
		// Variables
		
		    // Setting the following lines to true will disable them. This is in case
		    // the server that is running the bot does not support IPv4 or IPv6.
		    // Error handling may not be implemented in further down so do make
		    // sure this is configured correctly!
		$noIPv4 = false;
		$noIPv6 = false;
		
		    // How many times do you want the ping command to cycle? (Default: 4)
		$pingCount = 4;
		
		    // Pre-Fun things
            // This removes the https:// and http:// from the front of the $args
            // just incase the user left them there
            
            $args = str_ireplace(array("http://", "https://"), array("", ""), $args);
        
        
        	// IPv4 Handling
        	// This detects if the input args is an IPv4 address and runs if
        	// it is.
        if (filter_var($args , FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
        {
            //IPv6
            
                // Executes the "ping" command and stores the data in the array "$data"
                // The data is split at newlines
            $data = shell_exec("ping6 -c $pingCount -v -q " . escapeshellcmd($args));
	    	$data = preg_split('/[\r\n]+/', $data, -1, PREG_SPLIT_NO_EMPTY);
	    	
	    	    // Grabs the IP Address from the first line
	    	$ip = explode(" ", $data[0]);
	    	$ip = $ip[2];
	    	
	    	    // Grabs the total time taken from the 3rd line
	    	$total = explode(" ", $data[2]);
	    	$total_count = count($total) - 1;
	    	$total = str_ireplace("ms", "", $total[$total_count]);
	    	
	    	    // Grabs the average from the final line
	    	$average = explode(" ", $data[3]);
	    	$average = explode("/", $average[3]);
	    	$average = $average[1];
	    	
	    	if ($this->getConfig('commands.fancyOutput'))
            {
                $this->plugins->send->send($source, "\x02\x0304Pinged \x0305$args $ip \x0304a total of \x0305$pingCount \x0304times. Total time: \x0305$total \x0304ms. Average: \x0305$average \x0304ms.", $nick);
            } else {
                $this->plugins->send->send($source, "Pinged $args $ip a total of $pingCount times. Total time: $total ms. Average: $average ms.", $nick);
            }
            
        } else
        
        	// Hostmask Handling
        	// This detects if the input args is a hostmask and runs if
        	// it is.
        if (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $args) && preg_match("/^.{1,253}$/", $args) && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $args))
        {
            //Hostmask
                // Executes the "ping" command and stores the data in the array "$data"
                // The data is split at newlines
            $data = shell_exec("ping6 -c $pingCount -v -q " . escapeshellcmd($args));
	    	$data = preg_split('/[\r\n]+/', $data, -1, PREG_SPLIT_NO_EMPTY);
	    	
	    	    // Grabs the IP Address from the first line
	    	$ip = explode(" ", $data[0]);
	    	$ip = $ip[2];
	    	
	    	    // Grabs the total time taken from the 3rd line
	    	$total = explode(" ", $data[2]);
	    	$total_count = count($total) - 1;
	    	$total = str_ireplace("ms", "", $total[$total_count]);
	    	
	    	    // Grabs the average from the final line
	    	$average = explode(" ", $data[3]);
	    	$average = explode("/", $average[3]);
	    	$average = $average[1];
	    	
	    	if ($this->getConfig('commands.fancyOutput'))
            {
                $this->plugins->send->send($source, "\x02\x0304Pinged \x0305$args $ip \x0304a total of \x0305$pingCount \x0304times. Total time: \x0305$total \x0304ms. Average: \x0305$average \x0304ms.", $nick);
            } else {
                $this->plugins->send->send($source, "Pinged $args $ip a total of $pingCount times. Total time: $total ms. Average: $average ms.", $nick);
            }
            
        } else {
                // It will only get here if it fails the IPv4, IPv6 and Hostname checks. This is just some
                // extra security and also doubles up as a way of stopping people including variables for
                // for the host command in their arguments.
            if ($this->getConfig('commands.fancyOutput'))
            {
                $this->plugins->send->send($source, "\x02\x0304This is not a valid IPv6 or Hostname", $nick);
            } else {
                $this->plugins->send->send($source, "This is not a valid IPv6 or Hostname", $nick);
            }
        } 
    }
    
}