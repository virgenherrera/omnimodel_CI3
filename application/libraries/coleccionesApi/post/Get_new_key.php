<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get_new_key
{
	protected $ci;

	public function __construct(){
        $this->ci =& get_instance();
	}

	public function index($postParams){
		return [$postParams];
	}

}

/* End of file Get_new_key.php */
/* Location: ./application/libraries/coleccionesApi/post/Get_new_key.php */
