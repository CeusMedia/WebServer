<?php
/**
 *	Handler for Exceptions thrown while handling Request.
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
 *	@package		PAWS.Exception
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 *	@since			???
 *	@version		$Id$
 */
/**
 *	Handler for Exceptions thrown while handling Request.
 *	@category		cmModules
 *	@package		PAWS.Exception
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 *	@since			???
 *	@version		$Id$
 */
class CMM_PAWS_Exception_Handler {

	public function __construct($config) {
		$this->config	 = $config;
	}

	public function handle(CMM_PAWS_Exception $e) {
		$path		= $this->config['errorpages'];
		$code		= $e->getCode();
		$message	= $e->getMessage();
		$response	= new CMM_PAWS_Response($e->getCode());

		switch($e->getCode()) {
			case 405:
				$header	= new CMM_PAWS_Header('Allow', $this->config['methods']);
				$response->addHeader($header);
				break;
		}

		$pathName	= $path.$code.".html";
		if(!file_exists($pathName))
			throw new RuntimeException('Page for HTTP error "'.$code.'" not found');
		$data	= array(
			'type'		=> get_class($e),
			'message'	=> nl2br($e->getMessage()),
			'code'		=> $e->getCode(),
			'date'		=> date('c'),
		);
		$body	= UI_Template::render($pathName, $data);
		$response->setBody($body);
		return $response->toString();
	}
}
?>