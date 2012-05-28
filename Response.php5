<?php
/**
 *	HTTP Response.
 *
 *	Copyright (c) 2010 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmModules
 *	@package		PAWS
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 *	@since			???
 *	@version		$Id$
 */
/**
 *	HTTP Response.
 *	@category		cmModules
 *	@package		PAWS
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 *	@since			???
 *	@version		$Id$
 */
class CMM_PAWS_Response {

	protected $body		= NULL;
	protected $headers	= array();
	protected $protocol	= 'HTTP/1.0';
	protected $status	= "200 OK";

	public function addHeader(CMM_PAWS_Header $header) {
		array_push($this->headers, $header);
	}

	public function __construct($status = NULL) {
		if($status) {
			$this->setStatus($status);
		}
		$this->addHeader(new CMM_PAWS_Header('Server', 'PAWS/0.1'));
		$this->addHeader(new CMM_PAWS_Header('Date', date('r')));
		$this->addHeader(new CMM_PAWS_Header('Accept-Range', 'bytes'));
	}

	public function getHeaders() {
		return $this->headers;
	}

	public function setBody($body) {
		$this->body	= $body;
		$header		= new CMM_PAWS_Header('Content-length', strlen($body));
		$this->addHeader($header);
	}

	public function setStatus($status) {
		$this->status	= $status;
	}

	public function toString() {
		$lines	= array();
		array_push( $lines, $this->protocol.' '.$this->status );
		foreach($this->headers as $header) {
			array_push( $lines, $header->toString() );
		}
		if( $this->body ) {
			array_push( $lines, '' );												//  empty line between header and body
			array_push( $lines, $this->body );
		}
		return implode("\n", $lines);
	}
}
?>