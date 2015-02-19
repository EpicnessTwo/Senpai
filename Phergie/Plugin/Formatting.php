<?php
class Phergie_Plugin_Formatting extends Phergie_Plugin_Abstract
{
 
 // This plugin is used to manage formatting for text. Run the text though
 // Any of the following functions to make the output awesome!
 
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
    
}