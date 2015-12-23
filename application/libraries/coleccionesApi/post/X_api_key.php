<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class X_api_key
{
	protected $ci;

	public function __construct(){
        $this->ci =& get_instance();
	}

	public function index($postParams){
		return [$postParams];
	}

	

}

/* End of file X_api_key.php */
/* Location: ./application/libraries/coleccionesApi/post/X_api_key.php */
