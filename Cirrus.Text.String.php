<?php

  class String
  {
    /**************************************************************************
     * Public Properties
     **************************************************************************/

    public $Error;                      // current error status

    /**************************************************************************
     * Public Methods
     **************************************************************************/

    public function __construct ()
    {
    } // public function __construct ()


    public function __destruct ()
    {
    } // public function __destruct ()


    public function Left ($sText, $iCount)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //   Returns the left number of characters in a string.
    //
    // PARAMETERS:
    //   sText (in) - text to be truncated
    //   iCount (in) - number of characters to truncate
    //
    // RETURN:
    //    string - left $iCount number of characters
    //
    // EXAMPLE:
    //    Left ('1234567890', 2) = 12
    ////////////////////////////////////////////////////////////////////////////

    {
      return substr ($sText, 0, $iCount);
    } // public function Left ($sText, $iCount)


    public function Right ($sText, $iCount)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //   Returns the right number of characters in a string.
    //
    // PARAMETERS:
    //   sText (in) - text to be truncated
    //   iCount (in) - number of characters to truncate
    //
    // RETURN:
    //    string - right $iCount number of characters
    //
    // EXAMPLE:
    //    Right ('1234567890', 2) = 90
    ////////////////////////////////////////////////////////////////////////////

    {
      return substr ($sText, strlen ($sText) - $iCount, $iCount);
    } // public function Right ($sText, $iCount)


    public function TrimLeft ($sText, $iCount)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //   Cuts the leftmost of characters in a string.
    //
    // PARAMETERS:
    //   sText (in) - text to be truncated
    //   iCount (in) - number of characters to cut
    //
    // RETURN:
    //    string - the string after leftmost characters are cut
    //
    // EXAMPLE:
    //    TrimLeft ('1234567890', 2) = 34567890
    ////////////////////////////////////////////////////////////////////////////

    {
      return substr ($sText, $iCount, strlen ($sText) - $iCount);
    } // public function TrimLeft ($sText, $iCount)


    public function TrimRight ($sText, $iCount)

    ////////////////////////////////////////////////////////////////////////////
    // PURPOSE:
    //   Cuts the rightmost of characters in a string.
    //
    // PARAMETERS:
    //   sText (in) - text to be truncated
    //   iCount (in) - number of characters to cut
    //
    // RETURN:
    //    string - the string after rightmost characters are cut
    //
    // EXAMPLE:
    //    TrimRight ('1234567890', 2) = 12345678
    ////////////////////////////////////////////////////////////////////////////

    {
      return substr ($sText, 0, strlen ($sText) - $iCount);
    } // public function TrimRight ($sText, $iCount)
  } // class String

?>