<?php
/**
 *	HTTP Header.
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
 *	HTTP Header.
 *	@category		Library
 *	@package		CeusMedia_WebServer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/WebServer
 */
class Header {

	protected $key;
	protected $value;

	public function __construct($key, $value = NULL) {
		if(NULL !== $value) {
			$this->setKey($key);
			$this->setValue($value);
		}
		else
			$this->fromString($key);
	}

	public function fromString($string) {
		if(!substr_count($string, ':'))
			return FALSE;
		$parts	= explode(':', $string);
		$this->key	= ucfirst(strtolower(trim(array_shift($parts))));
		$value		= trim(implode(':',$parts));
		if(!substr_count($value, ';')) {
			$this->value	= $value;
			return TRUE;
		}
		$parts	= explode(';', $value);
		foreach($parts as $part) {
			$part	= trim($part);
			if(!substr_count($part, '=')) {
				$this->value[$part]	= NULL;
				continue;
			}
			$pair	= explode('=', $part);
			if(!empty($pair[1])) {
				if($pair[1][0] == '"')
					$pair[1]	= substr($pair[1], 1, -1);
				$this->value[$pair[0]] = $pair[1];
			}
		}
		return TRUE;
	}

	public function getKey() {
		return $this->key;
	}

	public function getValue() {
		return $this->value;
	}

	public function setKey($key) {
		$this->key		= $key;
	}

	public function setValue($value) {
		if(!is_string($value) && !is_array($value) && !is_int($value))
			throw new \InvalidArgumentException('Must be string, integer or array');
		$this->value	= $value;
	}

	public function toString() {
		if(!($this->key && $this->value))
			return '';
		if(is_string($this->value) || is_int($this->value))
			return $this->key.': '.$this->value;
		$list	= array();
		foreach($this->value as $key => $value)
			$list[]	= $value ? $key.'='.$value : $key;
		return $this->key.': '.implode('; ', $list);
	}
}
?>
