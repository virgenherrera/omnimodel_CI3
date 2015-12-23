<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Donaciones{
	protected $ci;

	public function __construct(){
        $this->ci =& get_instance();
	}

	public function index($postParams){
		$insertSet['tabla'] = 'donaciones';
		$insertSet['set']	= $postParams;
		return $this->ci->modelo->_insertt($insertSet);
	}

}

/* End of file Donaciones.php */
/* Location: ./application/libraries/coleccionesApi/post/Donaciones.php */