<?php
/**
 *	Dispatcher to run Handler for called HTTP Method.
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
 *	@package		CeusMedia_WebServer_Method
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/WebServer
 */
namespace CeusMedia\WebServer\Method;
/**
 *	Dispatcher to run Handler for called HTTP Method.
 *	@category		Library
 *	@package		CeusMedia_WebServer_Method
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/WebServer
 */
class Dispatcher {

	protected $config;
	protected $methods	= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$config			Configuration
	 *	@return		void
	 */
	public function __construct($config) {
		$this->config	= $config;
		foreach(explode(',',$config['methods']) as $method)
			$this->methods[]	= trim($method);
	}

	/**
	 *	Transports already set HTTP Headers to Response.
	 *	@access		public
	 *	@param		\CeusMedia\WebServer\Response	$response		HTTP Response Object
	 *	@return		void
	 */
	protected function adoptSetHeadersToResponse(\CeusMedia\WebServer\Response $response) {
#		remark('adoption');
		foreach(headers_list() as $header) {
			$parts = explode(':', $header);
			$key	= trim(array_shift($parts));
			$value	= trim(array_shift(implode(':',$parts)));
			$header	= new \CeusMedia\WebServer\Header($key, $value);
			$response->addHeader($header);
#			remark('adopt: '.$key);
		}
	}

	/**
	 *	Starts Handler Object for called HTTP Method, if supported and allowed.
	 *	@access		public
	 *	@param		\CeusMedia\WebServer\Request	$request		HTTP Request Object
	 *	@param		\CeusMedia\WebServer\Response	$response		HTTP Response Object
	 *	@return		void
	 */
	public function dispatch(\CeusMedia\WebServer\Request $request, CMM_PAWS_Response $response) {
		$method	= strtoupper(trim($request->getMethod()));

		if(!in_array($method,$this->methods)){														// check called Method agains Configuration
			$message	= 'HTTP Method "'.$method.'" is not supported';								// Method is not allowed
			throw new CMM_PAWS_Exception($message, 405);											// quit with 405
		}

		$methodName	= ucFirst(strtolower($method));													// Class File Name of Method
		$className	= '\\CeusMedia\\WebServer\\Method\\'.$methodName;								// Class Name of Method
		$parameters	= array($this->config);
		$handler	= \Alg_Object_Factory::createObject($className, $parameters);					// create Method Handler Object
		$body		= $handler->handle($request, $response);										// handle Request

		$this->adoptSetHeadersToResponse($response);												// transport inner set Headers to Response
		if($body){																					// generated Content is available
			$response->setBody($body);																// set Content to Response Body
		}
	}
}
?>
