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
            'nick' => 'NotSenpaiKitten',
            // 'password' => 'password goes here if needed',
            // 'transport' => 'ssl', // uncomment to connect using SSL
            // 'encoding' => 'UTF-8', // uncomment if using UTF-8
            // 'context' => array('socket' => array('bindto' => '0.0.0.0:0')), // uncomment to force use of IPv4
        )
    ),

    'processor' => 'async',
    'processor.options' => array('usec' => 200000),
    // Time zone. See: http://www.php.net/manual/en/timezones.php
    'timezone' => 'BST',

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
		'Random',
		'Utilities',
		'Games',
    ),

    // If set to true, this allows any plugin dependencies for plugins
    // listed in the 'plugins' option to be loaded even if they are not
    // explicitly included in that list
    'plugins.autoload' => true,

    // Enables shell output describing bot events via Phergie_Ui_Console
    'ui.enabled' => true,

    // Examples of a prefix for command-based plugins
    'command.prefix' => '~',
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
	
	'output.prefix' => '', // Example "> This is a bot output!"
	
	// The following config item will be what is used before formatting options
	// when using commands that are handled with the Formatting.php plugin.
	
	'format.prefix' => "&",
	
	// Now this is fun! The following one will stop any nick insterted from
	// being called out when using the randuser formatting tool
	
	'randuser.blacklist' => array("EpicKitty"),
	
	// The following command stops people from being pinged when the randuser
	// command is ran.
	
	'randuser.noPing' => true,
	
	// Now this is something to make it look a little better! Fancy Outputs
	// is pretty much as it says, it makes the output on some commands look
	// nicer!
	
	'commands.fancyOutput' => true,
	
	// The following can be toggled to true if the bot is being used on a
	// network that supports Halfops, Admins and Owners. For Espernet, this
	// is not used.
	
	'utils.advancedStatus' => false,
	
	// The following array is used for the topic command that is currently
	// stashed inside of the Utils plugin. This is an array to allow more
	// than one line of topic to be sent at a time.
	
	'utils.topic' => array(
	            "\x0304EpicKitty\x0f's Second Topic:",
	            "If EpicKitty isn't around, you can contact him via the links on https://epickitty.uk at the bottom or via email 'me@epickitty.uk'"
	        ),
	
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
	
	'permission.owner' => array("epickitty@i.love.furries.ga", "meow@dont.drink.that.racist.coffee", "epickitty@epickitty.uk", "anna@borealis.voxelstorm.com"),
	
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
