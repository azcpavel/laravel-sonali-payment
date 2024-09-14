<?php

namespace Exceptio\SonaliPayment;

function namespacePath($file){
	$filePath = str_replace(app_path(), 'App', $file);
	return str_replace('/','\\',$filePath);
}

function namespaceBasePath($file, $base_path = true){
	if(!$base_path)
		return $file;
	$base_name = explode('\\',$file);
	return end($base_name);
}
?>