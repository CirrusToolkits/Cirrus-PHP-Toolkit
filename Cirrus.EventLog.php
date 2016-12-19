<?php

/// APPLICATION: Cirrus PHP Toolkit
/// LIBRARY: Cirrus.Log
/// VERSION: 1.0.0
/// DATE: May 3, 2016
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


class EventLog
{
  const ERROR_NONE = 0;               // No error.
  const ERROR_UNKNOWN = 1;            // Unknown error.
  const ERROR_NO_SERVER = 2;          // Server not found.
  const ERROR_NO_LOGIN = 3;           // No user logged in.
  const ERROR_NO_DATABASE = 4;        // Database not found.
  const ERROR_NO_USER = 5;            // No user logged in.
  const ERROR_INVALID_LOGTYPE = 6;    // Invalid logging method;


  /**************************************************************************
   * Public Properties
   **************************************************************************/
   
    
  public $Error;                      // current error status


  /**************************************************************************
   * Private Properties
   **************************************************************************/

     
  private $_db_host;
  private $_db_user;
  private $_db_password;
  private $_db_database;
	private $_db_table;
	
	private $_logPath;
	private $_logFile;
	
	private $_loggingMethod;

            
  /**************************************************************************
   * Constructors
   **************************************************************************/

   
  public function __construct ($type, $a, $b = '', $c = '', $d = '', $e = '')
  {
  	$this->SetLoggingMethod ($type, $a, $b, $c, $d, $e);
  }
  
 
  public function __destruct ()
	{
		if ($this->_loggingMethod == 'file')
		  $this->CloseFile ();
	}
 
 
  /**************************************************************************
   * Methods
   **************************************************************************/

 
  private function CloseFile ()
  {
  	fclose ($this->_logFile);
  }
	
   
  private function CreateFile ()
	{
		$this->_logFile = fopen($this->_logPath, "w") or die ("Unable to create file!");
		
		fwrite ($this->_logFile, $this->GetLogStyle ());
	}
   
	 
	private function GetLogStyle ()
	{
		$style = "<style>\r\n"
		       . "  * { font: normal 10pt/12pt Arial, Helvetica, sans-serif; text-indent: 5px; }\r\n"
		       . "  .bold { font-family: 'Arial Black', Arial, sans-serif; font-weight: bold; }\r\n"		       
		       . "  .ok { color: black; margin 1px 0 1px 0; }\r\n"
		       . "  .error {background: red; color: white; margin: 1px 0 1px 0; 1}\r\n"
		       . "  .app { font-weight: bold; color: green; }\r\n"
		       . "  .desc { font: italic 10pt/12pt 'Times New Roman', Georgia, serif; color: blue; }\r\n"
		       . "</style>\r\n"
		       . "\r\n";
					 
		return $style;
	}
	
	 
  public function SetLoggingMethod ($type, $a, $b = '', $c = '', $d = '', $e = '')
	{
		$this->Error = self::ERROR_NONE;
		$this->_loggingMethod = $type;
		
		switch (strtolower ($type))
		{
			case 'database' :
        $this->_db_host = $a;
		    $this->_db_user = $b;
	      $this->_db_password = $c;
		    $this->_db_database = $d;
				$this->_db_table = $e;				
				break;
				
			case 'file' :
				$this->_logPath = $a;
				$this->CreateFile ();				
				break;
				
			default :
				$this->Error = self::ERROR_INVALID_LOGTYPE;
				$this->_loggingMethod = '';
		}
	}
   

  public function WriteLog ($log_id, $operation, $log_type, $app_name, $app_version, $referral, $details)
  {
  	$this->Error = self::ERROR_NONE;
  	
  	switch ($this->_loggingMethod)
		{
			case 'database' :
				$db = new PDOdatabase ('MySQL', $this->_db_host, $this->_db_database, $this->_db_user, $this->_db_password);

        $sql = "INSERT INTO {$this->_db_table} "
             . "("
             . "  log_id,"
             . "  operation, log_type,"
             . "  app_name, app_version,"
             . "  referral, details"
             . ") "
             . "VALUES "
             . "("
             . "  '$log_id',"
             . "  '$operation', '$log_type',"
             . "  '$app_name', '$app_version',"
             . "  '$referral', '$details'"
             . ")";

    		$data = $db->Link->query ($sql);
				
				break;
		
			case 'file' :
				$logText = "<div class='ok'>" 
				         . date ('Y-m-d H:i:s')
								 . ", <span class='bold'>$log_id</span>, $operation, $status, <span class='app'>$app_name</span>, <span class='app'>$app_version</span>, $referral, <span class='desc'>$description</span>"
								 . "</div>\r\n";
        fwrite ($this->_logFile, $logText);
				
				break;
				
			default :
				$this->Error = self::ERROR_INVALID_LOGTYPE;
				echo 'error';
	  } // switch ($this->_loggingMethod)
	  
	  return ($this->Error == self::ERROR_NONE);
	}

} // class EventLog

?>