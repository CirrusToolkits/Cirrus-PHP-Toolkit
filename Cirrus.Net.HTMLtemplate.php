<?php

/// APPLICATION: Cirrus PHP Toolkit
/// LIBRARY: Cirrus.Net.HTMLtemplate
/// VERSION: 1.0.0
/// DATE: July 28, 2015
/// AUTHOR: Johan Cyprich
/// AUTHOR URL: www.cyprich.com
/// AUTHOR EMAIL: jcyprich@live.com
///   
/// LICENSE:
/// The MIT License (MIT)
/// http://opensource.org/licenses/MIT
///
/// Copyright (c) 2015 Johan Cyprich. All rights reserved.
///
/// Permission is hereby granted, free of charge, to any person obtaining a copy 
/// of this software and associated documentation files (the "Software"), to deal
/// in the Software without restriction, including without limitation the rights
/// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
/// copies of the Software, and to permit persons to whom the Software is
/// furnished to do so, subject to the following conditions:
///
/// The above copyright notice and this permission notice shall be included in
/// all copies or substantial portions of the Software.
///
/// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
/// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
/// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
/// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
/// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
/// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
/// THE SOFTWARE.
///
/// SUMMARY:
/// 


class HTMLtemplate
{
  const ERROR_NONE = 0;                    // No error.
  const ERROR_UNKNOWN = 1;                 // Unknown error.
  const ERROR_NO_SERVER = 2;               // Server not found.
  const ERROR_NO_LOGIN = 3;                // No user logged in.
  const ERROR_NO_DATABASE = 4;             // Database not found.
  const ERROR_NO_USER = 5;                 // No user logged in.
  const ERROR_UNKNOWN_TEMPLATE_TYPE = 6;   // Unknown template type.
	const ERROR_FILE_NOT_FOUND = 7;          // File not found.
	const ERROR_FILE_WRITE = 8;              // Error writing to file.
	
	private $_errorList = array
  (
    0 => 'ERROR_NONE',
    1 => 'ERROR_UNKNOWN',
    2 => 'ERROR_NO_SERVER',
    3 => 'ERROR_NO_LOGIN',
    4 => 'ERROR_NO_DATABASE',
    5 => 'ERROR_NO_USER',
    6 => 'ERROR_UNKNOWN_TEMPLATE_TYPE',   
	  7 => 'ERROR_FILE_NOT_FOUND',
	  8 => 'ERROR_FILE_WRITE'			      
  );


  /**************************************************************************
   * Public Properties
   **************************************************************************/
    
  public $Error;                      // current error status


  /**************************************************************************
   * Private Properties
   **************************************************************************/
  
  private $_varArray;
  private $_htmlVersion;
	private $_templateType;
	private $_template;
	
            
  /**************************************************************************
   * Constructors
   **************************************************************************/

  public function __construct ($template, $htmlVersion = 'html5')
  {
  	$this->Error = self::ERROR_NONE;
		
		$this->_varArray = array ();
		$this->_htmlVersion = $htmlVersion;		
		$this->SetTemplateType ($template);
		
		$this->OpenFile ($template);
  } // public function __construct ()
  
  
  public function __destruct ()
  {
  	//$this->Close ();
  } // public function __destruct ()
	

  /****[ Attributes ]*************************************************/
  
	
	public function GetTemplateType () { return $this->_templateType; }
	
	public function SetTemplateType ($template)
	{
		$this->Error = self::ERROR_NONE;
		
  	$ext = strtolower (pathinfo ($template, PATHINFO_EXTENSION));
		
	  switch ($ext)
		{
			case 'tpl' :
				$this->_templateType = 'TPL';
				break;
				
			case 'tplx' :
				$this->_templateType = 'TPLX';
				break;
				
			default :
				$this->_templateType = 'Unknown';
				$this->Error = self::ERROR_UNKNOWN_TEMPLATE_TYPE;
		}
	}
		
		
  /**************************************************************************
   * Methods
   **************************************************************************/


  public function Assign ($var, $value)
  {
    $this->_varArray [$var] = $value; 
  }

  /*
  private function Close ()
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
  */
  
  
  private function OpenFile ($filename)
	{
		if (file_exists ($filename))
		{
		  $this->Error = self::ERROR_NONE;	
		  $this->_template = file_get_contents ($filename);
		}
		
		else
		{
			$this->Error = self::ERROR_FILE_NOT_FOUND;
			$this->_template = '';
		}
		
		$this->WriteError ($this->Error);
		
		return $this->Error;
	} // private function OpenFile ($filename)
  

  public function OutputText ()
	{
		return $this->ReplaceVars ();
	}
	
	
	public function OutputFile ($path)
	{
		$len = file_put_contents ($path, $this->ReplaceVars ());
		
		if ($len)
		  $this->Error = self::ERROR_NONE;
		
		else
		{
			$this->Error = self::ERROR_FILE_WRITE;
			$this->WriteError (self::ERROR_FILE_WRITE);
		}
		
		return $len;
	}


  public function PrintVariables ($html = true)
	{
		$cr = ($html) ? '<br />' : "\r\n";
		
		foreach ($this->_varArray as $x => $x_value)
      echo $x . ' => ' . $x_value . $cr;
	}


  private function ReplaceVars ()
  {
  	$template = $this->_template;
		 
    foreach ($this->_varArray as $x => $x_value)
      $template = str_replace ('{$' . $x . '}', $x_value, $template);

    return $template;
  }   
	  
	
	public function WriteError ($error, $html = 'true')	
	{
		if ($error == 0)
		  return;
		
		$cr = ($html) ? '<br />' : "\r\n";
		$message = "*** {$this->_errorList [$error]} ***$cr";
		
		echo $cr;		
		echo ($html) ? "<span style='color:red;'>$message</span>" : $message;		
		echo $cr;
	}
	
} // class HTMLtemplate

?>