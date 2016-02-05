<?php
	function GetDirectoryListing($root, $path){
		$dir_list = array();
		foreach (glob($root.$path.'/*') as $file) {
			$file = realpath($file);
			$link = substr($file, strlen($root) + 1);
			if(!is_file($file)){
			$dir_list[] = array(
								'link' => urlencode($link),
								'basename' => basename(substr($file, strlen($root) + 1)),
								'filesize' => filesize($file),
								'last_modified' => @date('d-m-Y h:ia', filemtime($file))
								);
			}
		}
		foreach (glob($root.$path.'/*') as $file) {
			$file = realpath($file);
			$link = substr($file, strlen($root) + 1);
			if(is_file($file)){
			$dir_list[] = array(
								'link' => urlencode($link),
								'basename' => basename(substr($file, strlen($root) + 1)),
								'filesize' => filesize($file),
								'last_modified' => @date('d-m-Y h:ia', filemtime($file))
								);
			}
		}
		return $dir_list;
	}