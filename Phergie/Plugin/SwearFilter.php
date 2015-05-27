<?php
class Phergie_Plugin_SwearFilter extends Phergie_Plugin_Abstract
{
    public function onLoad()
    {
        $this->getPluginHandler()->getPlugin('Command');
		$this->getPluginHandler()->getPlugin('Send');
		$this->getPluginHandler()->getPlugin('Permission');
		$this->getPluginHandler()->getPlugin('Formatting');
    }
 
    public function filter($input)
    {
        /*
         * This is a pretty simple plugin for this bot. It takes an array of bad words
         * and replaces it with a counterpart from the $replacement_words array. Simple!
         *
         * You can also edit these directly to maybe swap the words with something more nice
         * instead of staring out the words like I have. Yes, please laugh at the words I have
         * used because some of them may be a little stupid. This is just a proof of concept
         * to show that words can be filtered from the bot.
         *
         * To use this, please include the following line in the onLoad function in the plugin
         * you want to use...
         *
         * $this->getPluginHandler()->getPlugin('SwearFilter');
         *
         * ... and the following can be used as an example output from the bot using the filter.
         *
         * $this->plugins->send->send($source, $this->plugins->swearFilter->filter($output), $nick);
         *
         * From the above line, the following varibles are used:
         *
         * $source - The source of the command (A channel or nickname)
         * $output - The unfiltered output
         * $nick - The nickname of the user who executed the command
         *
         * Note: The comments after each array item is not needed, it just helps when debugging and
         * matching items to eachother on the fly and it looks a little more professional :D
         */
        
        $bad_words = array(
                "cookie", // Item 0
                "cake", // Item 1
                "kamran", // Item 2
            );
        $replacement_words = array(
                "c****e", // Item 0
                "c**e", // Item 1
                "Justin Bieber", // Item 2
            );
        
        return str_ireplace($bad_words, $replacement_words, $input);
    }
}