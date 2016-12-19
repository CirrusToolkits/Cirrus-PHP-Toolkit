<?php

  class FTPclient

  /////////////////////////////////////////////////////////////////////////////
  // class.FTPclient.php
  // Version 1.00
  // Copyright (C) 2015 Johan Cyprich. All rights reserved.
  // www.cyprich.com
  // July 17, 2015
  //
  // PURPOSE:
  //   Create a PHP class around the default FTP library to make it easier to
  //   use.
  /////////////////////////////////////////////////////////////////////////////

  {
    const ERROR_NONE = 0;               // no error
    const ERROR_UNKNOWN = 1;            // unknown error
    const ERROR_HOST = 2;               // host was not found
    const ERROR_ACCOUNT = 3;            // bad user name or password

    /**************************************************************************
     * Public Properties
     **************************************************************************/

    public $Error;                      // current error status

    /**************************************************************************
     * Private Properties
     **************************************************************************/

    private $m_Conn;                   // FTP connection ID
    private $m_sHost;                  // FTP server host
    private $m_sUserName;              // user name to login to FTP server
    private $m_sPassword;              // password to login to FTP server

    private $m_iTransferMode;          // type of FTP file transfer (FTP_ASCII or FTP_BINARY)
    private $m_bPassiveMode;
    private $m_iTimeOut;
    private $m_bAutoSeek;

    /**************************************************************************
     * Public Methods
     **************************************************************************/

    public function __construct ($sHost, $sUserName, $sPassword)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //   Connect to FTP server and set default values for connection.
    //
    // PUBLIC PROPERTIES:
    //   Error (in) -
    //
    // MEMBER VARIABLES:
    //   m_sHost (in) - FTP server host
    //   m_sUserName (in) - user name to connect to FTP server
    //   m_sPassword (in) - password to connect to FTP server
    //   m_iTransferMode (out) - type of FTP file transfer (FTP_ASCII or FTP_BINARY)
    //   m_bPassiveMode (out) -
    //   m_iTimeOut (out) -
    //   m_bAutoSeek (out) -
    ////////////////////////////////////////////////////////////////////////////

    {
      $this->Error = self::ERROR_NONE;

      $this->m_sHost = $sHost;
      $this->m_sUserName = $sUserName;
      $this->m_sPassword = $sPassword;

      $this->Connect ($sHost, $sUserName, $sPassword);

      $this->m_iTransferMode = FTP_BINARY;
      $this->m_bPassiveMode = false;
      $this->m_iTimeOut = 90;
      $this->m_bAutoSeek = true;
    } // public function __construct ()


    public function __destruct ()

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //   Close the FTP connection.
    ////////////////////////////////////////////////////////////////////////////

    {
      $this->Close ();
    } // public function __destruct ()


    public function ChDir ($sDirectory)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //   Change to a new directory on the FTP server.
    //
    // PARAMETERS:
    //   sDirectory (in) - directory to change to on the FTP server
    //
    // MEMBER VARIABLES:
    //   m_Conn - FTP connection ID
    //
    // RETURN:
    //   True - directory successfully changed
    //   False - directory not changed
    ////////////////////////////////////////////////////////////////////////////

    {
      return ftp_chdir ($this->m_Conn, $sDirectory);
    } // public function ChDir ($sDirectory)


    public function ChDir_Parent ()

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //   Changes to the parent directory on the FTP server.
    //
    // MEMBER VARIABLES:
    //   m_Conn (in) - FTP connection ID
    //
    // RETURN:
    //   True - directory successfully changed
    //   False - directory not changed
    ////////////////////////////////////////////////////////////////////////////

    {
      return ftp_cdup ($this->m_Conn);
    } // public function ChDir_Parent ()


    public function ChMod ($iMode, $sFilename)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //   Sets permissions on a file.
    //
    // PARAMETERS:
    //   iMode (in) - new permissions (octal value)
    //   sFilename (in) - file with permissions to modify
    //
    // MEMBER VARIABLES:
    //   m_Conn (in) - FTP connection ID
    //
    // RETURN:
    //   If successful, returns iMode; otherwise returns false.
    //////////////////////////////////////////////////////////////////////////

    {
      return ftp_chmod ($this->m_Conn, $iMode, $sFilename);
    } // public function ChMod ($iMode, $sFilename)


    public function Close ()

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //   Close the FTP connection.
    //
    // MEMBER VARIABLES:
    //   m_Conn (in) - FTP connection ID
    ////////////////////////////////////////////////////////////////////////////

    {
      ftp_close ($this->m_Conn);
    } // public function Close ()


    public function Command ($sCommand, $bDetailed = false)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //   Send a command to the FTP server.
    //
    // PARAMETERS:
    //   sCommand (in) - command to execute
    //   bDetailed (in) - determines the type of value to return in function
    //
    // MEMBER VARIABLES:
    //   m_Conn (in) - FTP connection ID
    //
    // RETURN:
    //   If bDetailed is true, then the server response is sent back as an array
    //   of strings if the command was successful. If bDetailed is false, then
    //   true will be returned if the command was successful. False will be
    //   returned if the command in either setting was not successful.
    ////////////////////////////////////////////////////////////////////////////

    {
      if ($bDetailed)
        $Result = ftp_raw ($this->m_Conn, $sCommand);

      else
        $Result = ftp_exec ($this->m_Conn, $sCommand);

      return $Result;
    } // public function Command ($sCommand)


    public function Connect ($sHost, $sUserName, $sPassword)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //   Opens a connection to an FTP server.
    //
    // PARAMETERS:
    //   sHost (in) -FTP server host
    //   sUserName (in) - user name to connect to FTP server
    //   sPassword (in) - password to connect to FTP server
    //
    // MEMBER VARIABLES:
    //   Error (out) - error status
    //   m_Conn (in) - FTP connection ID
    //
    // RETURN:
    //   ERROR_NONE - if no errors occured
    //   ERROR_HOST - host was not found
    //   ERROR_ACCOUNT - bad user name or password
    ////////////////////////////////////////////////////////////////////////////

    {
      // Connect to host.

      $this->m_Conn = ftp_connect ($sHost);

      // Login with user information.

      if ($this->m_Conn)
      {
        $bLoginResult = ftp_login ($this->m_Conn, $sUserName, $sPassword);

        if (!$bLoginResult)
          $this->Error = self::ERROR_ACCOUNT;
      } // if ($this->m_Conn)

      else
        $this->Error = self::ERROR_HOST;

      return $this->Error;
    } // public function Connect ()


    public function ContinueTransfer ()

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //   Continue sending or receiving a file in an asynchronous file transfer.
    //
    // MEMBER VARIABLES:
    //   m_Conn (in) - FTP connection ID
    //
    // RETURN:
    //    FTP_FAILED
    //    FTP_FINISHED
    //    FTP_MOREDATA
    ////////////////////////////////////////////////////////////////////////////

    {
      return ftp_nb_continue ($this->m_Conn);
    } // public function ContinueTransfer ()


    public function DeleteFile ($sFilename)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //   Deletes a file on the FTP server.
    //
    // PARAMETERS:
    //   sFilename (in) - filename to delete
    //
    // MEMBER VARIABLES:
    //   m_Conn (in) - FTP connection ID
    //
    // RETURN:
    //   True - file was deleted
    //   False - file was not deleted
    ////////////////////////////////////////////////////////////////////////////

    {
      return ftp_delete ($this->m_Conn, $sFilename);
    } // public function DeleteFile ($sFilename)


    public function Download ($sSource, $sDestination = '', $bSynchronous = false)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //   Download a file from the FTP server and save on the local drive.
    //
    // PARAMETERS:
    //   sSource (in) -
    //   sDestination (in) -
    //   bSynchronous (in) -
    //
    // MEMBER VARIABLES:
    //   m_Conn (in) - FTP connection ID
    //
    // RETURN:
    //   True - file was downloaded
    //   False - file was not downloaded
    ////////////////////////////////////////////////////////////////////////////

    {
      if ($sDestination == '')
        $sDestination = $sSource;

      if ($bSynchronous)
        $bResult = ftp_nb_get ($this->m_Conn, $sDestination, $sSource, $this->m_iTransferMode);

      else
        $bResult = ftp_get ($this->m_Conn, $sDestination, $sSource, $this->m_iTransferMode);

      return $bResult;
    } // public function Download ($sSource, $sDestination = '', $bSynchronous = false)


    public function GetAutoSeek ()

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    ////////////////////////////////////////////////////////////////////////////

    {
      return $this->m_iAutoSeek;
    } // public function GetAutoSeek ()


    public function GetCurrentDirectory ()

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //
    // MEMBER VARIABLES:
    //   m_Conn (in) - FTP connection ID
    ////////////////////////////////////////////////////////////////////////////

    {
      return ftp_pwd ($this->m_Conn);
    } // public function GetCurrentDirectory ()


    public function GetDirectory ($sDirectory, $bDetailed = false, $bRecursive = false)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //
    // MEMBER VARIABLES:
    //   m_Conn (in) - FTP connection ID
    ////////////////////////////////////////////////////////////////////////////

    {
      if ($bDetailed)
        $Contents = ftp_rawlist ($this->m_Conn, $sDirectory, $bRecursive);

      else
        $Contents = ftp_nlist ($this->m_Conn, $sDirectory);

      return $Contents;
    } // public function GetDirectory ($sDirectory, $bDetailed, $bRecursive)


    public function GetFileSize ($sFilename)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //
    // MEMBER VARIABLES:
    //   m_Conn (in) - FTP connection ID
    ////////////////////////////////////////////////////////////////////////////

    {
      return ftp_size ($this->m_Conn, $sFilename);
    } // public function GetFileSize ($sFilename)


    public function GetLastModifiedTime ($sFilename)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //
    // MEMBER VARIABLES:
    //   m_Conn (in) - FTP connection ID
    ////////////////////////////////////////////////////////////////////////////

    {
      return ftp_mdtm ($this->m_Conn, $sFilename);
    } // public function GetLastModifiedTime ($sFilename)


    public function GetSystemType ()

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //
    // MEMBER VARIABLES:
    //   m_Conn (in) - FTP connection ID
    ////////////////////////////////////////////////////////////////////////////

    {
      return ftp_systype ($this->m_Conn);
    } // public function GetSystemType ()


    public function GetPassiveMode ()

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    ////////////////////////////////////////////////////////////////////////////

    {
      return $this->m_GetPassiveMode;
    } // public function GetPassiveMode ()


    public function GetTimeOut ()

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    ////////////////////////////////////////////////////////////////////////////

    {
      return $this->m_bTimeOut;
    } // public function GetTimeOut ()


    public function GetTransferMode ()

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    ////////////////////////////////////////////////////////////////////////////

    {
      return $this->m_iTransferMode;
    } // public function GetTransferMode ()


    public function MkDir ($sDirectory, $bChangeDir = false)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //
    // MEMBER VARIABLES:
    //   m_Conn (in) - FTP connection ID
    ////////////////////////////////////////////////////////////////////////////

    {
      $bSuccess = false;

      if ($bChangeDir)
      {
        $bSuccess = ftp_mkdir ($this->m_Conn, $sDirectory);
        $this->ChDir ($sDirectory);
      } // if ($bChangeDir)

      else
        $bSuccess = ftp_mkdir ($this->m_Conn, $sDirectory);

      return $bSuccess;
    } // public function MkDir ($sDirectory)


    public function Rename ($sOldName, $sNewName)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //   Renames a file or folder.
    //
    // MEMBER VARIABLES:
    //   m_Conn (in) - FTP connection ID
    ////////////////////////////////////////////////////////////////////////////

    {
      return ftp_rename ($this->m_Conn, $sOldName, $sNewName);
    } // public function Rename ($sOldNamae, $sNewName)


    public function SetAutoSeek ($bSeek)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    ////////////////////////////////////////////////////////////////////////////

    {
      $this->m_bAutoSeek = $bSeek;
    } // public function SetAutoSeek ($bSeek)


    public function SetPassiveMode ($bPassive)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //
    // MEMBER VARIABLES:
    //   m_Conn (in) - FTP connection ID
    ////////////////////////////////////////////////////////////////////////////

    {
      $this->m_bPassiveMode = $bPassive;
      ftp_pasv ($this->m_Conn, $bPassive);
    } // public function SetPassiveMode ($bPassive)


    public function SetTimeOut ($iTimeOut)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    ////////////////////////////////////////////////////////////////////////////

    {
      if ($iTimeOut > 0)
        $this->m_iTimeOut = $iTimeOut;

      else
        $this->m_iTimeOut = 90;
    } // public function SetTimeOut ($iTimeOut)


    public function SetTransferMode ($iMode)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    ////////////////////////////////////////////////////////////////////////////

    {
      if (($iMode == FTP_ASCII) || ($iMode == FTP_BINARY))
        $this->m_iTransferMode = $iMode;

      else
        $this->m_iTransferMode = FTP_BINARY;
    } // public function SetTransferMode ($iMode)


    public function Upload ($sSource, $sDestination = '', $bSynchronous = false)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //
    // MEMBER VARIABLES:
    //   m_Conn (in) - FTP connection ID
    ////////////////////////////////////////////////////////////////////////////

    {
      if ($sDestination == '')
        $sDestination = $sSource;

      if ($bSynchronous)
        $bResult = ftp_nb_put ($this->m_Conn, $sDestination, $sSource, $this->m_iTransferMode);

      else
        $bResult = ftp_put ($this->m_Conn, $sDestination, $sSource, $this->m_iTransferMode);

      return $bResult;
    } // public function Upload ($sSource, $sDestination = '', $bSynchronous = false)
  } // class FTPclient

?>