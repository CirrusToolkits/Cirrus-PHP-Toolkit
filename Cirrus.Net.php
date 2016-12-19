<?php

class Net
{
  const ERROR_NONE = 0;               // no error
  const ERROR_UNKNOWN = 1;            // unknown error
  const ERROR_NO_SERVER = 2;          // server was not found
  const ERROR_NO_LOGIN = 3;           // no user is logged in
  const ERROR_NO_DATABASE = 4;        // database was not found
  const ERROR_NO_USER = 5;            // no user is logged in


  /**************************************************************************
   * Public Properties
   **************************************************************************/
    
  public $Error;                      // current error status


  /**************************************************************************
   * Constructors
   **************************************************************************/


  public function __construct ()
  {
  } // public function __construct ()


  /**************************************************************************
   * Private Properties
   **************************************************************************/
  
            
  /**************************************************************************
   * Public Methods
   **************************************************************************/

   
  // Returns the IP of the current web site.

  public function GetIP ()
  {
    return $_SERVER['SERVER_NAME'];
  }
  

  // Returns the name of the current web page.

  public function GetPageName ()
  {
    return substr ($_SERVER ["SCRIPT_NAME"], strrpos ($_SERVER["SCRIPT_NAME"], "/") + 1);
  }
  
  
  // Returns URL of current web page.
 
  public function GetPageURL ()
  {
    $url = 'http';
    
	if ($_SERVER ["HTTPS"] == "on")
	  $url .= "s";

    $url .= "://";
	  
    if ($_SERVER ["SERVER_PORT"] != "80")
      $url .= $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"];
   
    else
      $url .= $_SERVER ["SERVER_NAME"] . $_SERVER ["REQUEST_URI"];
 
    return $url;
  }

  
  // Returns the port of the current web site.__CLASS__
  
  public function GetPort ()
  {
    return $_SERVER['SERVER_PORT'];
  }


  public function GetSiteURL ()
  {
    $site = new Net ();
  
    return $site->GetIP () . ':' . $site->GetPort ();
  }  
  
} // class Cirrus.Net

?>