<?php
/**
 *	Handler for HTTP Method GET.
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
 *	Handler for HTTP Method GET.
 *	@category		Library
 *	@package		CeusMedia_WebServer_Method
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/WebServer
 *	@extends		\CeusMedia\WebServer\MethodAbstract
 */
class Get extends \CeusMedia\WebServer\MethodAbstract {

	protected $name		= 'GET';

	/**
	 *	Handles Request.
	 *	@access		public
	 *	@param		\CeusMedia\WebServer\Request		$request		HTTP Request Object
	 *	@param		\CeusMedia\WebServer\Response	$response		HTTP Response Object
	 *	@return		string
	 */
	public function handle(\CeusMedia\WebServer\Request $request, \CeusMedia\WebServer\Response $response) {
		$response->addHeader(new \CeusMedia\WebServer\Header("Connection", "close"));				//
		$this->logRequest($request);
		$pathName	= $this->negotiatePath($request);
		$fileName	= pathinfo($pathName, PATHINFO_BASENAME);
//		echo " . get file: ".$fileName."\n";
		$extension	= pathinfo($pathName, PATHINFO_EXTENSION);
#		if(array_key_exists($extension, $this->mimeTypes))
#			$response->addHeader('Content-type', $this->mimeTypes[$extension]);
		if(preg_match($this->config['executable'], $fileName)) {
			return $this->runPhpFile($request, $response, $pathName);
		}
		if(is_dir($pathName)){
			$handler	= new \CeusMedia\WebServer\Response\Index($this->config);
			return $handler->handle($request, $response);
		}
//		$response->addHeader(new \CeusMedia\WebServer\Header("Modified-At", filemtime($pathName)));

		$fileSize	= filesize($pathName);
		$fileTime	= date("r", filemtime($pathName));												//  get file timestamp (RFC 2822)
		$mimeType	= finfo_file(finfo_open(FILEINFO_MIME_TYPE), $pathName);						//  get mime type using mimetype extension
		$response->addHeader(new \CeusMedia\WebServer\Header("Content-Type", $mimeType));			//  set mime type header
		$response->addHeader(new \CeusMedia\WebServer\Header("Content-length",  $fileSize));		//  set content length header
		$response->addHeader(new \CeusMedia\WebServer\Header("Last-Modified", $fileTime));			//  set content timestamp header
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
	 *	@param		\CeusMedia\WebServer\Request	$request		HTTP Request Object
	 *	@param		\CeusMedia\WebServer\Response	$response		HTTP Response Object
	 *	@param		string			$pathName		Working Path
	 *	@return		string			Generated Content
	 *	@throws		CMM_PAWS_Exception if PHP throws uncatched Exception
	 */
	protected function runPhpFile($request, $response, $pathName) {
		try {
			$response->addHeader(new \CeusMedia\WebServer\Header("Cache-Control", "no-cache"));
			return $this->fork($pathName, $request);
		}
		catch(\Exception $e) {
			$trace	= $e->getTraceAsString();
			$msg	= $e->getMessage();
			$file	= $e->getFile();
			$line	= $e->getLine();
			$e	= new \CeusMedia\WebServer\Exception($msg.' in '.$file.' in line '.$line, 500);
			$e->setUri(parse_url($request->getUrl(), PHP_URL_PATH));
			throw $e;
		}
	}
}
?>
