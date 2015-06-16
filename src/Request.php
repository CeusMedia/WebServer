<?php
/**
 *	HTTP Request.
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
 *	HTTP Request.
 *	@category		Library
 *	@package		CeusMedia_WebServer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/WebServer
 */
class Request {

	protected $body		= NULL;
	protected $config	= array();
	protected $headers	= array();
	protected $method	= NULL;
	protected $methods	= array();
	protected $parts	= array();
	protected $protocol	= NULL;
	protected $url		= NULL;

	public function __construct($config) {
		$this->config	= $config;
		$this->methods	= explode(',',$this->config['methods']);
	}

	public function addHeader(\CeusMedia\WebServer\Header $header) {
		array_push($this->headers, $header);
	}

	public function fromString($string) {
		$lines	= explode("\n", $string);
		$first	= array_shift($lines);
		$parts	= explode(' ',$first);
		if(count($parts) != 3) {
			$e	= new \CeusMedia\WebServer\Exception('Invalid HTTP header', 400);
			$e->setUri($path);
			throw $e;
		}

		$method		= strtoupper(array_shift($parts));
/*		if(!in_array($method, $this->methods)) {
			$e	= new \CeusMedia\WebServer\Exception('Method "'.$method.'" is not available', 405);
//			$e->setUri($path);
			throw $e;
		}*/
		$this->setMethod($method);

		$url		= trim(urldecode(array_shift($parts)));
		if(strlen($url) > $this->config['limit.url']) {
			$e	= new \CeusMedia\WebServer\Exception('The URL is to long', 414);
			$e->setUri($url);
			throw $e;
		}
		$this->setUrl($url);

		$this->setProtocol(array_shift($parts));

		$boundary	= NULL;
		while(NULL !== ($line = trim(array_shift($lines)))) {
			if(empty($line))
				break;
			$this->addHeader(new \CeusMedia\WebServer\Header($line));
/*			if(!$boundary){
				$header		= $request->getHeaderByKey('Content-type');
				if($header) {
					$parts	= $header->getValue();
					print_m($parts);
					if(array_key_exists('boundary', $parts))
						$boundary	= $parts['boundary'];
				}
			}
#			if($boundary)
#			{
*/		}
		$this->setBody(join("\n",$lines));
	}

	public function getBody() {
		return $this->body;
	}

	public function getHeaderByKey($key) {
		$key	= strtolower($key);
		foreach($this->headers as $header)
			if(strtolower($header->getKey()) == $key)
				return $header;
		return NULL;
	}

	public function getHeaders() {
		return $this->headers;
	}

	public function getMethod() {
		return $this->method;
	}

	public function getParts() {
		return $this->parts;
	}

	public function getProtocol() {
		return $this->protocol;
	}

	public function getUrl() {
		return $this->url;
	}

	public function setBody($body) {
		$this->body	= $body;
	}

	public function setMethod($method) {
		$this->method	= $method;
	}

	public function setProtocol($protocol) {
		$this->protocol	= $protocol;
	}

	public function setUrl($url) {
		$this->url	= $url;
	}
}
?>
