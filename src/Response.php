<?php
/**
 *	HTTP Response.
 *
 *	Copyright (c) 2010-2015 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_WebServer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/WebServer
 */
namespace CeusMedia\WebServer;
/**
 *	HTTP Response.
 *	@category		Library
 *	@package		CeusMedia_WebServer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/WebServer
 */
class Response {

	protected $body		= NULL;
	protected $headers	= array();
	protected $protocol	= 'HTTP/1.0';
	protected $status	= "200 OK";

	public function addHeader(\CeusMedia\WebServer\Header $header) {
		array_push($this->headers, $header);
	}

	public function __construct($status = NULL) {
		if($status) {
			$this->setStatus($status);
		}
		$this->addHeader(new \CeusMedia\WebServer\Header('Server', 'PAWS/0.1'));
		$this->addHeader(new \CeusMedia\WebServer\Header('Date', date('r')));
		$this->addHeader(new \CeusMedia\WebServer\Header('Accept-Range', 'bytes'));
	}

	public function getHeaders() {
		return $this->headers;
	}

	public function setBody($body) {
		$this->body	= $body;
		$header		= new \CeusMedia\WebServer\Header('Content-length', strlen($body));
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
