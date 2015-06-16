<?php
/**
 *	Handler for Exceptions thrown while handling Request.
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
 *	@package		CeusMedia_WebServer_Response
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/WebServer
 */
namespace CeusMedia\WebServer\Response;
/**
 *	Handler for Exceptions thrown while handling Request.
 *	@category		Library
 *	@package		CeusMedia_WebServer_Response
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/WebServer
 */
class Error {

	public function __construct($config) {
		$this->config	 = $config;
	}

	public function handle(\CeusMedia\WebServer\Exception $e) {
		$path		= $this->config['errorpages'];
		$code		= $e->getCode();
		$message	= $e->getMessage();
		$response	= new \CeusMedia\WebServer\Response($e->getCode());

		switch($e->getCode()) {
			case 405:
				$header	= new \CeusMedia\WebServer\Header('Allow', $this->config['methods']);
				$response->addHeader($header);
				break;
		}

		$fileName	= $code.".html";
		if(!file_exists($path.$fileName))
			throw new \RuntimeException('Page for HTTP error "'.$code.'" not found');
		$data	= array(
			'type'		=> get_class($e),
			'message'	=> nl2br($e->getMessage()),
			'code'		=> $e->getCode(),
			'date'		=> date('c'),
			'path'		=> $e->getUri(),
		);
		try {
			$template	= new \CeusMedia\TemplateEngine\Template();
			$template->setTemplatePath($path);
			$template->addPlugin(new \CeusMedia\TemplateEngine\Plugin\Inclusion());
			$body		= $template->renderFile($fileName, $data);
//			$body	= \UI_Template::render($pathName, $data);
			$response->setBody($body);
		}
		catch(\Exception $e) {
			$response->setStatus(500);
			$response->setBody('Internal Server Error');
		}
		return $response->toString();
	}
}
?>
