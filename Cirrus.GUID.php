<?php

/// APPLICATION: Cirrus PHP Toolkit
/// LIBRARY: Database
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


class GUID
{
	//=== Properties ==============================================================================


	//=== Methods =================================================================================


	////////////////////////////////////////////////////////////////////////////
	/// PURPOSE:
	/// Generates 1 to n random hex digits in a sequential string.
	///
	/// PARAMETERS:
	/// $n (in) - the number of digits to generate
	///
	/// RETURN:
	/// string - 1 to n lower case hex digits
	////////////////////////////////////////////////////////////////////////////

	private function HexDigits ($n)
	{
		$digits = '';

		for ($i = 1; $i <= $n; $i++)
			$digits .= dechex (rand (0, 15));

		return $digits;
	}


	////////////////////////////////////////////////////////////////////////////
	/// PURPOSE:
	/// Generates a GUID.
	///
	/// PARAMETERS:
	/// ucase (in) - if true, returns string as uppercase
	///
	/// RETURN:
	/// string - GUID
	////////////////////////////////////////////////////////////////////////////

	public function Generate ($ucase = false)
	{
		$guid = $this->HexDigits (8) . '-'
			  . $this->HexDigits (4) . '-'
			  . $this->HexDigits (4) . '-'
			  . $this->HexDigits (4) . '-'
			  . $this->HexDigits (12);

		return ($ucase) ? strtoupper ($guid) : $guid;
	}
}