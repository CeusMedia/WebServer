<?php
/**
 *	Daemon to run Server as a Service.
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
 *	Daemon to run Server as a Service.
 *	@category		Library
 *	@package		CeusMedia_WebServer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/WebServer
 */
class ServerDaemon
{
	public function __construct()
	{
		$pid = pcntl_fork();
		if($pid == -1)
			throw new \RuntimeException('Could not fork');
		if(!$pid)
			new \CeusMedia\WebServer\Server;
	}
}
?>
