<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('print_array')){
	function print_array($data,$die=false){
		echo "<pre>";
		var_dump($data);
		echo "</pre><br>";
		if($die){
			die();
		}
	}
}


//fin del helper