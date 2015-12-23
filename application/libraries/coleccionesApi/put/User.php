<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User{
	protected $ci;

	public function __construct(){
        $this->ci =& get_instance();
	}

	public function index($id,$putParams){
		return ['id'=>$id,'params'=>$putParams];
	}

	

}

/* End of file User.php */
/* Location: ./application/libraries/coleccionesApi/put/User.php */