<?php 

  class System
  {
  	const ERROR_NONE = 0;               // no error
    const ERROR_UNKNOWN = 1;            // unknown error
    const ERROR_NO_SERVER = 2;          // server was not found
    const ERROR_NO_LOGIN = 3;           // no user is logged in
    const ERROR_NO_DATABASE = 4;        // database was not found
    const ERROR_NO_USER = 5;            // no user is logged in
    
    public $Error;
  	
    /************************************************************************
     * Public members.
     ************************************************************************/
            
    public function __construct ()
    {
    } // public function __construct ()
        
        
    public function __destruct ()
    {
    } // public function __destruct ()

    
    public function CopyFile ($sSource, $sDestination)
    {
    	system ("cp $sSource $sDestination");
    } // public CopyFile ($sSource, $sDestination)
	
	
	public function RenameFile ($sOldName, $sNewName)
	{
	  system ("mv $sOldName $sNewName");
	} // public function RenameFile ($sOldName, $sNewName)
  } // class System

?>