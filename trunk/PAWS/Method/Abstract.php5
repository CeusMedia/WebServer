<?php
/**
 *	Method Handler Abstraction.
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
 *	Method Handler Abstraction.
 *	@category		cmModules
 *	@package		PAWS.Method
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 *	@since			???
 *	@version		$Id$
 */
abstract class PAWS_Method_Abstract extends Console_Fork_Abstract{

	protected $name	= 'UNKNOWN';

	public function __construct($config) {
		parent::__construct(TRUE);
		$this->config		= $config;
		$this->mimeTypes	= parse_ini_file($config['mime.file']);
	}

	abstract public function handle(PAWS_Request $request, PAWS_Response $response);

	protected function logRequest(PAWS_Request $request) {
		$message	= time().' '.$this->name.' '.$request->getUrl()."\n";
		$fileLog	= $this->config['log.access'];
		if(!is_writable(dirname($fileLog)))
			throw new RuntimeException('Log file "'.$fileLog.'" is not writable' );
		error_log($message, 3, $fileLog);
	}

	protected function negotiatePath(PAWS_Request $request) {
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
		throw new PAWS_Exception('No resource found for URL "'.$path.'"', 404);
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
