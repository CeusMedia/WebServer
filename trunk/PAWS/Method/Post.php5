<?php
/**
 *	Handler for HTTP Method POST.
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
 *	Handler for HTTP Method POST.
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
class CMM_PAWS_Method_Post extends CMM_PAWS_Method_Get {

	protected $name		= 'POST';

	protected function runInChild($arguments = array()) {
		$pathName	= array_shift($arguments);
		$request	= array_shift($arguments);
		$this->setGet($request);
		$this->setPost($request);

		ob_start();
		require_once($pathName);
		$output	= ob_get_clean();
#		header('Content-type: text/html',FALSE);
		return $output;
	}

	protected function setPost(CMM_PAWS_Request $request) {
		$parts	= $request->getParts();
		if($parts) {
			foreach($parts as $part) {
				$header	= $part->getHeaderByKey('Content-disposition');
				$value	= $header->getValue();
				if($part->isFile()) {
					$content	= $part->getContent();
					$error		= 0;
					$size		= 0;
					if(strlen($content) > getEnv('upload_max_filesize'))
						$error	= UPLOAD_ERR_INI_SIZE;

					$tmpId	= tempname();
					if(!@file_put_contents($tmpId, $content))
						$error	= UPLOAD_ERR_CANT_WRITE;
					else
						$size	= filesize($tmpId);

					$header	= $part->getHeaderByKey('Content-disposition');
					$value	= $header->getValue();
					$file	= array(
						'name'		=> $value['file-name'],
						'type'		=> $part->getHeaderByKey('Content-type'),
						'size'		=> $size,
						'tmp_name'	=> $tmpId,
						'error'		=> $error,
					);
					$_FILES[$value['name']]	= $file;
				}
				else
				{
					$_POST[$value['name']]	= $part->getContent();
					$_REQUEST[$value['name']]	= $part->getContent();
				}
			}
		}
		else
		{
			$body	= trim($request->getBody());
	#		remark($body);
			parse_str($body, $_POST);
			parse_str($body, $_REQUEST);
		}
	}
}
?>