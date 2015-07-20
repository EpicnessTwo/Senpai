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
    $colarr = array("04", "08", "09", "11", "12", "13"); // Rainbow colours
		  $str = str_split($text); // Why am i not using explode here???? Oh yeah, you can explode at "" <-- Nothing
		  $output = "> \x02";
		  $count = count($str);
		  $col = 0;
		  
		  while ($c !== $count)
		  {
		  	if ($col == 6){$col = 0;} // If the color is at position 7, reset it back to position 1
		  	$out = ($out . "\x03" . $colarr[$col] . $str[$c]);
		  	$c++; 
		  	$col++;
		  }
		  
		  return $out;

 }
 
 public function extraFormatting($text)
 {
   
     $letters = range("a", "z");
   
     $input = array(
            $this->getConfig('format.prefix') . "nick",
            $this->getConfig('format.prefix') . "randcolour",
            $this->getConfig('format.prefix') . "chan",
            $this->getConfig('format.prefix') . "time",
            $this->getConfig('format.prefix') . "date",
            $this->getConfig('format.prefix') . "randnumber",
            $this->getConfig('format.prefix') . "randletter"
      );
      
     $output = array(
             $this->getEvent()->getNick(),
             "\x03" . rand(0,15),
             $this->getEvent()->getSource(),
             date('h:i:s A'),
             date('l jS \of F Y'),
             rand(1, 1000),
             $letters[rand(0, count($letters) - 1)],
             
      );
      
      
     $out = str_ireplace($input, $output, $text);
     
     $out = explode($this->getConfig('format.prefix') . "randuser", $out);
     
     $c = 1;
     
     $count = count($out);
     
     $output = $out[0];
     
     $blacklist = $this->getConfig('randuser.blacklist');
     
     while ($c !== $count)
     {
         $randuser = $this->plugins->userinfo->getRandomUser($this->getEvent()->getSource(), $blacklist);
         $blacklist[] = $randuser;
         
         if ($this->getConfig('randuser.noPing')){$randuser = "-" . $randuser;}
         
         $output .= $randuser . $out[$c];
         $c++;
     }
     
     return $output;
  
 }
    
}