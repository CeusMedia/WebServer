<?php
/**
 *	Method Handler Abstraction.
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
 *	Method Handler Abstraction.
 *	@category		Library
 *	@package		CeusMedia_WebServer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/WebServer
 */
abstract class MethodAbstract extends \Console_Fork_Abstract{

	protected $name	= 'UNKNOWN';

	public function __construct($config) {
		parent::__construct(TRUE);
		$this->config		= $config;
		$this->mimeTypes	= parse_ini_file($config['mime.file']);
	}

	abstract public function handle(\CeusMedia\WebServer\Request $request, \CeusMedia\WebServer\Response $response);

	protected function logRequest(\CeusMedia\WebServer\Request $request) {
		$message	= time().' '.$this->name.' '.$request->getUrl()."\n";
		$fileLog	= $this->config['log.access'];
		if(!is_writable(dirname($fileLog)))
			throw new \RuntimeException('Log file "'.$fileLog.'" is not writable' );
		error_log($message, 3, $fileLog);
	}

	protected function negotiatePath(\CeusMedia\WebServer\Request $request) {
		$root		= $this->config['docroot'];
		$indices	= explode(',', $this->config['index']);
		$path		= parse_url($request->getUrl(), PHP_URL_PATH);
		if(substr($path, -1) == "/") {
			foreach($indices as $index) {
				$probeUri	= $root.$path.trim($index);
				if(file_exists($probeUri)) {
					return $probeUri;
				}
			}
		}
		if(file_exists($root.$path))
			return $root.$path;
		throw new \CeusMedia\WebServer\Exception('No resource found for URL "'.$path.'"', 404);
	}

	protected function setGet($request) {
		$url	= $request->getUrl();
		$query	= parse_url($url, PHP_URL_QUERY);
		parse_str($query, $_GET);
		parse_str($query, $_REQUEST);
	}

	protected function runInParent($arguments = array()) {}
}
?>
