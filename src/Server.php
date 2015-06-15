<?php
/**
 *	HTTP Server.
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
 *	HTTP Server.
 *	@category		Library
 *	@package		CeusMedia_WebServer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/WebServer
 */
class Server extends \Console_Fork_Abstract {

	protected $config	= array();
	protected $socket	= NULL;
	protected $osIsWin	= NULL;
	protected $parser	= NULL;

	public function __construct($configFile = 'config/config.ini') {
		parent::__construct(FALSE);
		if(!file_exists($configFile))
			die("Configuration file is missing\n");
		$this->config	= parse_ini_file($configFile);
		$this->osIsWin	= strtoupper(substr(PHP_OS, 0, 3)) == 'WIN';

		ini_set("max_execution_time", "0");
		ini_set("max_input_time", "0");
		set_time_limit(0);
#		$this->parser	= new \CeusMedia\WebServer\Request\Parser($this->config);
		echo 'Server is running...'."\n";
		$this->main();
	}

	protected function createSocket() {
		$addr	= 'tcp://'.$this->config['host'].':'.$this->config['port'];
		$socket = stream_socket_server($addr, $errorNumber, $errorMessage, STREAM_SERVER_BIND|STREAM_SERVER_LISTEN);
		if(!$socket)
			throw new RuntimeException($errorMessage, $errorNumber);
		stream_set_blocking( $socket, 0 );
		return $socket;
	}

	protected function handleRequest($string) {
#		$request	= $this->parser->parse($string);
		$request	= new \CeusMedia\WebServer\Request($this->config);
		$request->fromString($string);
		$response	= new \CeusMedia\WebServer\Response();
		$handler	= new \CeusMedia\WebServer\Method\Dispatcher($this->config);
		$handler->dispatch($request, $response);
		return $response->toString();
	}

	public function handleShutdown($connection) {
        $error = error_get_last();
        if($error === NULL)
			return;
		if(!is_resource($connection))
			return;
		$msg = $error['message'].' in '.$error['file']. ' in line '.$error['line'];
		$exception	= new \CeusMedia\WebServer\Exception($msg, 500);
		$handler	= new \CeusMedia\WebServer\Exception_Handler($this->config);
		$response	= $handler->handle($exception);
		@fwrite($connection, $response);
		@fclose($connection);
    }

	protected function main() {
		$socket	= $this->createSocket();
		while(TRUE) {
			$connection	= @stream_socket_accept($socket);
			if($connection){
				$request	= @fread($connection, 65535);
echo array_shift( explode( "\r\n", $request ) )."\n";
				if($request) {
					if($this->osIsWin)
						$this->runInChild(array($connection, $request));
					else
						$this->fork($connection, $request);
				}
			}
		}
	}

	protected function runInChild($arguments = array()) {
		try {
			try {
				$connection	= array_shift($arguments);
				$request	= array_shift($arguments);
				$callback	= array($this, 'handleShutdown');
				register_shutdown_function($callback, $connection);
				$response	= $this->handleRequest($request);
			}
			catch(\CeusMedia\WebServer\Exception $exception) {
				$handler	= new \CeusMedia\WebServer\Exception\Handler($this->config);
				$response	= $handler->handle($exception);
			}
		}
		catch(\Exception $e){
			die($e->getMessage()."\n");
		}
		fwrite($connection, $response);
		fclose($connection);
		if(!$this->osIsWin)
			exit(0);
	}

	protected function runInParent($arguments = array()) {
	}
}
?>
