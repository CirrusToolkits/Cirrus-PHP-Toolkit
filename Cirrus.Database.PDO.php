<?php
		
class PDOdatabase
{
  const ERROR_NONE = 0;               // no error
  const ERROR_UNKNOWN = 1;            // unknown error
  const ERROR_NO_SERVER = 2;          // server was not found
  const ERROR_NO_LOGIN = 3;           // no user is logged in
  const ERROR_NO_DATABASE = 4;        // database was not found
  const ERROR_NO_USER = 5;            // no user is logged in

  const ERRORACTION_HALT = 0;         // halt script
	const ERRORACTION_IGNORE = 1;       // ignore error and continue

	  
  /**************************************************************************
   * Public Properties
   **************************************************************************/

       	
  public $Error;
  public $Link;

   
  /**************************************************************************
   * Private Properties
   **************************************************************************/
	
	private $_errorAction;
	  
  private $_dbServer;
  private $_hostName;
  private $_port;
  private $_protocol;
  private $_charSet;
  private $_database;
  private $_userName;
  private $_password;
  private $_dbPath;                // path to database including database name
        

  /**************************************************************************
   * Attributes
   **************************************************************************/
        
        
  public function GetDbServer ()
  {
    return $this->_dbServer;
  }
        
  public function SetDbServer ($dbServer)
  {
    $this->_dbServer = $dbServer;
  }
        
  //////////////////////////////////////////
       
  public function GetHostname ()
  {
    return $this->_hostName;
  } // public function GetHostname ()
        
  public function SetHostname ($hostName)
  {
    $this->_hostName = $hostName;
  }
       
  //////////////////////////////////////////

  public function GetPort ()
  {
    return $this->_port;
  }
        
  public function SetPort ($port)
  {
    $this->_port = $port;
  }

  //////////////////////////////////////////
        
  public function GetProtocol ()
  {
    return $this->m_sProtocol;
  }
        
  public function SetProtocol ($protocol)
  {
    $this->m_sProtocol = $protocol;
  }
        
  //////////////////////////////////////////
        
  public function GetCharSet ()
  {
    return $this->_charSet;
  }
        
  public function SetCharSet ($charSet)
  {
    $this->_charSet = $charSet;
  }        
        
  //////////////////////////////////////////
        
  public function GetDatabase ()
  {
    return $this->_database;
  }
        
  public function SetDatabase ($database)
  {
    $this->_database = $database;
  }
        
  //////////////////////////////////////////
        
  public function GetUsername ()
  {
    return $this->_userName;
  }
        
  public function SetUsername ($userName)
  {
    $this->_userName = $userName;
  }        
        
  //////////////////////////////////////////
      
  public function GetPassword ()
  {
    return $this->_password;          
  }
        
  public function SetPassword ($password)
  {
    $this->_password =  $password;
  }
        
  //////////////////////////////////////////
        
  public function GetDbPath ()
  {
    return $this->_dbPath;
  }
        
  public function SetDbPath ($dbPath)
  {
    $this->_dbPath = $dbPath;
  }

  //////////////////////////////////////////

  public function GetErrorAction ()
	{
		return $this->_errorAction;
	}
	
	public function SetErrorAction ($action)
	{
		switch (strtolower ($action))
		{
			case 'halt' :
				$this->_errorAction = self::ERRORACTION_HALT;
				break;
				
			case 'ignore' :
				$this->_errorAction = self::ERRORACTION_IGNORE;
				break;
				
			default :
				$this->_errorAction = self::ERRORACTION_HALT;
		}
	} 

		     
  /**************************************************************************
   * Constructors
   **************************************************************************/
            
            
  public function __construct ($dbServer = '', $hostName = '', $database = '', $userName = '', $password = '', $port = '', $protocol = '', $charSet = '', $dbPath = '')
	{
  	$this->Error = self::ERROR_NONE;
		$this->_errorAction = self::ERRORACTION_IGNORE;
		
    $this->_dbServer = $dbServer;
		$this->_hostName = $hostName;
		$this->_port = $port;
		$this->_protocol = $protocol;
		$this->_charSet = $charSet;
		$this->_database = $database;
		$this->_userName = $userName;
		$this->_password = $password;
		$this->_dbPath = $dbPath;
		
		$this->Connect ();
	}
        
    
  public function __destruct ()
  {
  	$this->Close ();
  }
        

  /**************************************************************************
   * Members
   **************************************************************************/

    
  public function Close ()
  {
    // Close database connection.
          
    $this->Link = null;    	
  }
    
    
  public function Connect ()
  {
    $dbServer = isset ($this->_dbServer) ? $this->_dbServer : '';
    $hostName = isset ($this->_hostName) ? $this->_hostName : '';
    $port = isset ($this->_port) ? $this->_port : '';
    $protocol = isset ($this->_protocol) ? $this->_protocol : '';
    $charSet = isset ($this->_charSet) ? $this->_charSet : '';          
    $database = isset ($this->_database) ? $this->_database : '';
    $userName = isset ($this->_userName) ? $this->_userName : '';
    $password = isset ($this->_password) ? $this->_password : '';
    $dbPath = isset ($this->_dbPath) ? $this->_dbPath : '';

    $this->Error = self::ERROR_NONE;
      
    try
    {
      switch ($dbServer)
      {
        case 'DBLIB' :
          $this->Link = new PDO ("dblib:host=$hostName:$port; dbname=$dbname", $userName, $password);
          break;
                
        case 'DB2' :
          $this->Link = new PDO("ibm:DRIVER={IBM DB2 ODBC DRIVER}; DATABASE=$database; HOSTNAME=$hostName; PORT=$port; PROTOCOL=$protocol;", $userName, $password);
          break;
                
        case 'Informix' :
          $this->Link = new PDO ("informix:DSN=$database", $userName, $password);
          break;
                                
        case 'MySQL' :
          $this->Link = new PDO ("mysql:host=$hostName; dbname=$database", $userName, $password);
          break;
				
        case 'MSSQL-WinAuth' :
					$this->Link = new PDO ("sqlsrv:server=$hostName;Database=$database", NULL, NULL);
					break;

        case 'MSSQL' :
					$this->Link = new PDO ("sqlsrv:server=$hostName;Database=$database", $userName, $password);
					break;
                
        case 'ODBC' :
          $this->Link = new PDO ("odbc:Driver={Microsoft Access Driver (*.mdb)};Dbq=$dbPath;Uid=$userName");
          break;
                
        case 'Oracle' :
          $this->Link = new PDO ("OCI:dbname=$database;charset=$charSet", $userName, $password);
          break;
              
        case 'PostgreSQL' : 
          $this->Link = new PDO ("pgsql:host=$hostName; dbname=$database", $userName, $password);
          break;

        case 'SQLite' :
          $this->Link = new PDO ("sqlite:$dbPath");
          break;                
      } // switch ($this->_dbServer)
      
      $this->Link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      
    } // try        

    catch (PDOException $e)
    {
    	if ($this->_errorAction == self::ERRORACTION_HALT)
        die ("<p style='color: red;'>ERROR: Connect :: {$e->getMessage ()}</span>");
        
      $this->Error = self::ERROR_NO_SERVER;
    } // catch (PDOException $e)          
  } // public function Connect ()
  
  
  public function GetAvailableDrivers ()
	{
		echo '<p>';
		
		foreach(PDO::getAvailableDrivers() as $driver)
      echo "$driver<br />";
		
		echo '</p>';
	}


  public function Exec ($sql)
	{
		$result = 0;                             // number of rows affected
						
 	  $result = $this->Link->exec ($sql);
		
    return $result;
	}     
   
	   
  public function SqlCmd ($sql, $message = '')
  {
    $cmd = $this->Link->prepare ($sql);

    if ($cmd->execute ())
    {
      if ($message != '')
        echo "$message\n";
    } // if (!$bResult)

    else
    {
      echo "SqlCmd: Query failed\n";
      exit ();
    } // else if (!$bResult)
  } // public function UpdateData ()

} // class PDOdatabase

?>
