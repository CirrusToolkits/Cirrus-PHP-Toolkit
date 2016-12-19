<?php

class XFDFwriter
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
  
  private $_XFDFfile;
  
              
  /**************************************************************************
   * Constructors
   **************************************************************************/

  public function __construct ($outputFile, $PDFpath)
  {
    $this->_XFDFfile = $outputFile;    
    $this->CreateFile ($PDFpath);
  }
  
  
  public function __destruct ()
  {
    $this->Close ();
  }
  
  
  /**************************************************************************
   * Private Methods
   **************************************************************************/
  
  private function Close ()
  { 
    $file = fopen ($this->_XFDFfile, 'a');
    
    fwrite ($file, "  </fields>\n");
    fwrite ($file, "</xfdf>");
    
    fclose ($file);    
  }
  
  
  private function CreateFile ($PDFpath)
  {
    $file = fopen ($this->_XFDFfile, 'w');
    
    fwrite ($file, '<?xml version="1.0" encoding="utf-8"?>' . "\n");
    fwrite ($file, '<xfdf xmlns="http://ns.adobe.com/xfdf/" xml:space="preserve">' . "\n");
    fwrite ($file, '  <f href="' . $PDFpath . '" />' . "\n");
    fwrite ($file, '  <ids original="F428988D376D6940876456823B025025" modified="5C9A2EA5AF253D44B77EF1FC914728C1" />' . "\n");
    fwrite ($file, "  <fields>\n");
    
    fclose ($file);
  }
  
  
  /**************************************************************************
   * Public Methods
   **************************************************************************/

  public function WriteField ($sField, $sValue)
  {
    $file = fopen ($this->_XFDFfile, 'a');
    
    fwrite ($file, "    <field name=\"$sField\">\n");
    fwrite ($file, "      <value>" . htmlentities ($sValue) . "</value>\n");
    fwrite ($file, "    </field>\n");
    
    fclose ($file);
  }
    
} // class XFDFwriter

?>