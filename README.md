Senpai the IRC Bot
=================

Originally written by the Phergie Team, this is a modified PHP IRC Bot that has been worked on by EpicKitty  
Currently, the bot has a few commands added but loads more needs adding!

Current Features:
 - Git manipulation (Requires "git") [Being removed due to buggyness]
 - Permission System
 - Configurable Output System
 - Formatting Tools For Output Manipulation
 - Customisable Prefixes for both Commands and Formatting
 
Future Features:
 - Even more commands
 - Another stage to the permission system that allows for channel status to be used as well as hostmasks
 - User info storage (Using a database)
    - Custom welcome messages
    - User specific permission system
    - Quotes storage
 - Web APIs
 - Database driven commands and config
    - Commands that can be called from the database. This allows for on the fly command adding and changing config items.

As most of it is written in a linux box, some things may not fully work on windows. I can't fully help this :<

When setting up this bot, you must change the Settings.php.temp to Settings.php and edit the connection settings towards the top of the file. This is used to allow the bot to connect.

Info
=====

Phergie is an IRC bot written for PHP 5.2.  

Main project web site: http://phergie.org  

Instructions for running your own instance of Phergie: http://phergie.org/users/  

Architectural overview for plugin developers: http://phergie.org/developers/  

Support: http://phergie.org/support/  

Bug reports/feature requests: http://github.com/phergie/phergie/issues  
