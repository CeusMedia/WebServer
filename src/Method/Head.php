<?php
/**
 *	Handler for HTTP Method HEAD.
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
 *	Handler for HTTP Method HEAD.
 *	@category		Library
 *	@package		CeusMedia_WebServer_Method
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/WebServer
 *	@extends		\CeusMedia\WebServer\Method\Get
 */
class Head extends \CeusMedia\WebServer\Method\Get {

	protected $name	= 'HEAD';

	/**
	 *	Handles HEAD Request.
	 *	@access		public
	 *	@param		\CeusMedia\WebServer\Request		$request		HTTP Request Object
	 *	@param		\CeusMedia\WebServer\Response	$response		HTTP Response Object
	 *	@return		string
	 */
	public function handle(\CeusMedia\WebServer\Request $request, \CeusMedia\WebServer\Response $response) {
		$content	= parent::handle($request, $response);
		$pathName	= $this->config['docroot'].parse_url($request->getUrl(), PHP_URL_PATH);
		$fileTime	= date("r", filemtime($pathName));												//  get file timestamp (RFC 2822)
		$mimeType	= finfo_file(finfo_open(FILEINFO_MIME_TYPE), $pathName);						//  get mime type using mimetype extension
		$response->addHeader(new \CeusMedia\WebServer\Header("Content-Type", $mimeType));			//  set mime type header
		$response->addHeader(new \CeusMedia\WebServer\Header("Content-length",  0));				//  set content length header
		$response->addHeader(new \CeusMedia\WebServer\Header("Last-Modified", $fileTime));			//  set content timestamp header
		return "";
	}
}
?>
