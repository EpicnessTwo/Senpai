<?php
class Phergie_Plugin_Formatting extends Phergie_Plugin_Abstract
{
 
 // This plugin is used to manage formatting for text. Run the text though
 // Any of the following functions to make the output awesome!
 
 public function onLoad()
 {
     $this->getPluginHandler()->getPlugin('UserInfo');
 }
 
 public function format($text)
 {
     $input = array(
            $this->getConfig('format.prefix') . "c", // Colour
            $this->getConfig('format.prefix') . "b", // Bold
            $this->getConfig('format.prefix') . "u", // Underline
            $this->getConfig('format.prefix') . "i", // Italics
            $this->getConfig('format.prefix') . "r", // Reset
            );
     
     $output = array(
            "\x03",
            "\x02",
            "\x1F",
            "\x1D",
            "\x0F",
            );
            
    $out = str_ireplace($input, $output, $text);
    
    return $out;
 }
 
 public function rainbow($text)
 {
     return "Broked!";
 }
 
 public function extraFormatting($text)
 {
     $input = array(
            $this->getConfig('format.prefix') . "nick",
            $this->getConfig('format.prefix') . "randuser",
            $this->getConfig('format.prefix') . "randcolour",
            $this->getConfig('format.prefix') . "chan",
            $this->getConfig('format.prefix') . "time",
            $this->getConfig('format.prefix') . "date",
      );
      
     $output = array(
             $this->getEvent()->getNick(),
             $this->plugins->userinfo->getRandomUser($this->getEvent()->getSource(), $this->getConfig('randuser.blacklist')),
             "\x03" . rand(0,15),
             $this->getEvent()->getSource(),
             date('h:i:s A'),
             date('l jS \of F Y'),
      );
      
     $out = str_ireplace($input, $output, $text);
     
     return $out;
  
 }
    
}