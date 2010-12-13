<?php
/**
 *	Handler for HTTP Method HEAD.
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
 *	Handler for HTTP Method HEAD.
 *	@category		cmModules
 *	@package		PAWS.Method
 *	@extends		CMM_PAWS_Method_Get
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 *	@since			???
 *	@version		$Id$
 */
class CMM_PAWS_Method_Head extends CMM_PAWS_Method_Get {

	protected $name	= 'HEAD';

	/**
	 *	Handles HEAD Request.
	 *	@access		public
	 *	@param		HttpRequest		$request		HTTP Request Object
	 *	@param		HttpResponse	$response		HTTP Response Object
	 *	@return <type>
	 */
	public function handle(HttpRequest $request, HttpResponse $response) {
		$content	= parent::handle($request, $response);
		$header		= new HttpHeader('Content-length', strlen($content));
		$response->addHeader($header);
		return NULL;
	}
}
?>