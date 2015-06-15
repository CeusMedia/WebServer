<?php
/**
 *	Part in HTTP Request or Response (for multipart and redirecting support).
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
 *	@package		CeusMedia_WebServer_Request
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/WebServer
 */
namespace CeusMedia\WebServer\Request;
/**
 *	Part in HTTP Request or Response (for multipart and redirecting support).
 *	@category		Library
 *	@package		CeusMedia_WebServer_Request
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/WebServer
 */
class Part {

	protected $content	= NULL;
	protected $headers	= array();

	public function __construct($lines) {
		while(NULL !== ($line = array_shift($lines))) {
			if(trim($line))
				$this->headers[]	= new \CeusMedia\WebServer\Header($line);
			else {
				$this->content	= implode("\n", $lines);
				break;
			}
		}
	}

	public function getContent(){
		return $this->content;
	}

	public function getHeaderByKey($key) {
		$key	= strtolower($key);
		foreach($this->headers as $header)
			if(strtolower($header->getKey()) == $key)
				return $header;
		return NULL;
	}

	public function getHeaders(){
		return $this->headers;
	}

	public function isFile(){
		foreach($this->headers as $header)
			if($header->getKey() == 'Content-disposition')
				if(array_key_exists('file-name', $header->getValue()))
					return TRUE;
		return FALSE;
	}
}
?>
