<?php
/**
 *	Handler for HTTP Method GET.
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
 *	@package		PAWS.Method
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 *	@since			???
 *	@version		$Id$
 */
/**
 *	Handler for HTTP Method GET.
 *	@category		cmModules
 *	@package		PAWS.Method
 *	@extends		PAWS_Method_Abstract
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 *	@since			???
 *	@version		$Id$
 */
class PAWS_Method_Get extends PAWS_Method_Abstract {

	protected $name		= 'GET';

	/**
	 *	Handles Request.
	 *	@access		public
	 *	@param		PAWS_Request		$request		HTTP Request Object
	 *	@param		PAWS_Response	$response		HTTP Response Object
	 *	@return <type>
	 */
	public function handle(PAWS_Request $request, PAWS_Response $response) {
		$this->logRequest($request);
		$pathName	= $this->negotiatePath($request);
		$fileName	= pathinfo($pathName, PATHINFO_BASENAME);
		$extension	= pathinfo($pathName, PATHINFO_EXTENSION);
#		if(array_key_exists($extension, $this->mimeTypes))
#			$response->addHeader('Content-type', $this->mimeTypes[$extension]);
		if(preg_match($this->config['executable'], $fileName)) {
			return $this->runPhpFile($pathName, $request);
		}
		return file_get_contents($pathName);
	}

	protected function runInChild($arguments = array()) {
		$pathName	= array_shift($arguments);
		$request	= array_shift($arguments);
		$this->setGet($request);

		ob_start();
		require_once($pathName);
		$output	= ob_get_clean();
#		header('Content-type: text/html',FALSE);
		return $output;
	}

	/**
	 *	Runs a PHP Script in forked Process.
	 *	@access		public
	 *	@param		string			$pathName		Working Path
	 *	@param		PAWS_Request	$request		HTTP Request Object
	 *	@return		string			Generated Content
	 *	@throws		PAWS_Exception if PHP throws uncatched Exception
	 */
	protected function runPhpFile($pathName, $request) {
		try {
			return $this->fork($pathName, $request);
		}
		catch(Exception $e) {
			$trace	= $e->getTraceAsString();
			$msg	= $e->getMessage();
			$file	= $e->getFile();
			$line	= $e->getLine();
			throw new PAWS_Exception($msg.' in '.$file.' in line '.$line, 500);
		}
	}
}
?>