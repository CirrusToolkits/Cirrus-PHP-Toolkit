<?php

class HTMLwriter
{
  const ERROR_NONE = 0;               // no error
  const ERROR_UNKNOWN = 1;            // unknown error


  /**************************************************************************
   * Public Properties
   **************************************************************************/
    
  public $Error;                      // current error status


  /**************************************************************************
   * Private Properties
   **************************************************************************/
  
  private $_HTMLfile;
  
  private $_webType;  
  private $_webType_Default_HTML5_header;
  private $_webType_Default_HTML5_footer;
  
              
  /**************************************************************************
   * Constructors
   **************************************************************************/

  public function __construct ($webType, $outputFile, $title = null)
  {
    $this->_HTMLfile = $outputFile;
    $this->_webType = $webType;
    
    $this->CreateWebTypes ($title);
    
    if ($title == null)
      $this->CreateFile (' ');
        
    else
      $this->CreateFile ($title);
  } // public function __construct ()
  
  
  public function __destruct ()
  {
    $this->Close ();
  }
  
  
  /**************************************************************************
   * Private Methods
   **************************************************************************/
  
  public function Close ()
  { 
    $file = fopen ($this->_HTMLfile, 'a');
    
    switch ($this->_webType)
    {
      case "Default_HTML5" :
        $this->WriteLine ($this->_webType_Default_HTML5_footer);
        break;
        
      default :
        $this->WriteLine ($this->_webType_Default_HTML5_footer);
        break;        
    }
    
    fclose ($file);    
  }
  
  
  private function CreateFile ($title)
  {
    $file = fopen ($this->_HTMLfile, 'w');
    
    switch ($this->_webType)
    {
      case "Default_HTML5" :
        $this->WriteLine ($this->_webType_Default_HTML5_header);
        break;
        
      default :
        $this->WriteLine ($this->_webType_Default_HTML5_header);
        break;        
    }
    
    fclose ($file);
  }
  

  private function CreateWebTypes ($title)
  {
    // "Default_HTML5"
    
    $this->_webType_Default_HTML5_header =
           "<!DOCTYPE html>\r\n"
         . "\r\n"
         . "<html>\r\n"
         . "  <head>\r\n"
         . "    <title>$title</title>\r\n"
         . "  </head>\r\n"
         . "\r\n"
         . "  <body>";
  
   $this->_webType_Default_HTML5_footer = 
          "  </body>\r\n"
         . "</html>";
  }

  
  /**************************************************************************
   * Public Methods
   **************************************************************************/

  public function AddMultilineTextBreaks ($text)
  {
    return str_replace ("\r\n", "<br />", $text);
  }


  public function Custom ($html)
  {
    $this->Write ($html);
  }


  public function EndTag ($tag)
  {
    $this->Write ("</$tag>");
  }


  public function LineBreak ()
  {
    $this->Write ("<br />\r\n");
  }


  public function StartTag ($tag)
  {
    $this->Write ("<$tag>");
  }
    

  public function Write ($text, $style = null)
  {
    $file = fopen ($this->_HTMLfile, 'a');
    
    if ($style == null) 
      fwrite ($file, $text);
    
    else
      fwrite ($file, "<span style='$style'>$text</span>");
      
    fclose ($file);
  }


  public function WriteBR ($text, $style = null)
  {
    if ($style == null)
      $this->Write ("$text<br />\r\n");
    
    else
      $this->Write ("<span style='$style'>$text</span><br />\r\n");
  }
  

  public function WriteField ($label, $labelStyle, $field, $fieldStyle, $cr = true)
  {
    $this->Write ($label, $labelStyle);
    $this->Write ($field, $fieldStyle);
    
    if ($cr)
      $this->LineBreak ();
  }


  public function WriteLine ($text)
  {
    $this->Write ("$text\r\n", null);
  }


  public function WriteParagraph ($text, $style = null)
  {
    if ($style == null)
      $this->Write ("<p>$text</p>\r\n");
    
    else
      $this->Write ("<p style='$style'>$text</p>\r\n");
  }
  
} // class HTMLwriter

?>