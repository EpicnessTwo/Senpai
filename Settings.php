<?php

return array(

    // One array per connection, pretty self-explanatory
    'connections' => array(
        // Ex: All connection info for the first network
        array(
            'host' => 'irc.esper.net',
            'port' => 6667,
            'username' => 'NotSenpai',
            'realname' => 'NotSenpai',
            'nick' => 'NotSenpai',
            // 'password' => 'password goes here if needed',
            // 'transport' => 'ssl', // uncomment to connect using SSL
            // 'encoding' => 'UTF-8', // uncomment if using UTF-8
            // 'context' => array('socket' => array('bindto' => '0.0.0.0:0')), // uncomment to force use of IPv4
        )
    ),

    'processor' => 'async',
    'processor.options' => array('usec' => 200000),
    // Time zone. See: http://www.php.net/manual/en/timezones.php
    'timezone' => 'UTC',

    // Whitelist of plugins to load
    'plugins' => array(
        // To enable a plugin, simply add a string to this array containing
        // the short name of the plugin as shown below.

        'AutoJoin',
        'Help',
        'Pong',
        'Ping',
		'Quit',
		'Owner',
		'CTCP',
		'Console',
		'General',
		'Formatting',
		'Cookies',
    ),

    // If set to true, this allows any plugin dependencies for plugins
    // listed in the 'plugins' option to be loaded even if they are not
    // explicitly included in that list
    'plugins.autoload' => true,

    // Enables shell output describing bot events via Phergie_Ui_Console
    'ui.enabled' => true,

    // Examples of a prefix for command-based plugins
    'command.prefix' => '-',
    // If you uncomment the line above, this would invoke onCommandJoin 
    // in the Join plugin: !join #channel
    // By default, no prefix is assumed, so the same command would be 
    // invoked like this: join #channel
    
    // Examples of supported values for autojoins.channel:
    'autojoin.channels' => '#Epic,#EpicBots',
    // 'autojoin.channels' => array('#channel1', '#channel2'),
    // 'autojoin.channels' => array(
    //                            'host1' => '#channel1,#channel2',
    //                            'host2' => array('#channel3', '#channel4')
    //                        ),

    // Examples of setting values for Ping plugin settings

    // This is the amount of time in seconds that the Ping plugin will wait
    // to receive an event from the server before it initiates a self-ping

    'ping.event' => 300, // 5 minutes

    // This is the amount of time in seconds that the Ping plugin will wait
    // following a self-ping attempt before it assumes that a response will
    // never be received and terminates the connection

    'ping.ping' => 10, // 10 seconds
	
	// This is the prefix that is shown before output of commands. This has
	// been added to try to prevent the output of this bot triggering other
	// bots.
	
	'output.prefix' => '&rand[-&nick] ', // Example "> This is a bot output!"
	
	// The following config item will be what is used before formatting options
	// when using commands that are handled with the Formatting.php plugin.
	
	'format.prefix' => "&",
	
	// The below config items are made for the permission system contained
	// inside the Permission.php file located in the Plugin folder. It allows
	// for 3 different permission levels, Owner, Admin and Blacklisted.
	//
	// Owner = Allows every command to be ran
	// Admin = Allows for all the standard commands plus the Join and Part
	// commands by default
	// Blacklisted = Stops the person from being able to run any commands at
	// all.
	//
	// To make things simple, please remove all capitals from the items below
	// as this will help with making sure everything works fine
	
	'permission.owner' => array("epickitty@i.love.furries.ga", "Someoneelse@hostmask.com"),
	
	'permission.admin' => array("cookiebot@bots.epickitty.uk", "someone@else.trusted"),
	
	'permission.black' => array("some!bl@cklist.ed"),
	
	// Below is a list of outputs that will be used if there is an error.
	
	// If the user running the command doesn't have the permissions to do so
	'error.noperms' => "\x0304Im sorry but I dont think you're allowed to do that :<",
	
	// For random errors that i really dont see a way of catogorising (Revise Spellings!)
	'error.misc' => "\x0304Sorry but I can't work out what just happened. Did you break something?",
	
	// For people who are blacklisted from using the bot using the permisson.black array above
	'error.blacklisted' => "\x0304Welp, you must have done something wrong because you are banned from using me!",
	
	
	
	// The version and name of the bot, used by the CTCP Version
	'bot.version' => 'Senpai Dev v0.1 - Written by EpicKitty on the Phergie Framework',

);
