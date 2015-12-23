<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User{
	protected $ci;

	public function __construct(){
        $this->ci =& get_instance();
	}

	public function index($getParams){
		$query['f'] = 'user';
		
		if($this->ci->uri->segment(3)>0){
			$query['w'] = ['id_user'=>$this->ci->uri->segment(3)];
			if( is_string($this->ci->uri->segment(4)) ){ $query['s'] = str_replace('-', ', ', $this->ci->uri->segment(4)); }
			$resultado =$this->ci->modelo->_gett($query);
			return ($resultado===FALSE)?NULL:$resultado;
		}
		if($this->ci->uri->segment(3)==='_'){
			$query['s'] = '*';
			if($this->ci->uri->segment(4)>0){ $query['limit'] = $this->ci->uri->segment(4); }
			if($this->ci->uri->segment(5)>0){ $query['offset'] = $this->ci->uri->segment(5); }
			$resultado =$this->ci->modelo->_gett($query);
			return ($resultado===FALSE)?NULL:$resultado;
		}
		if(is_string($this->ci->uri->segment(3))){
			if( is_string($this->ci->uri->segment(3)) ){ $query['s'] = str_replace('-', ', ', $this->ci->uri->segment(3)); }
			if($this->ci->uri->segment(4)>0){ $query['limit'] = $this->ci->uri->segment(4); }
			if($this->ci->uri->segment(5)>0){ $query['offset'] = $this->ci->uri->segment(5); }
			$resultado =$this->ci->modelo->_gett($query);
			return ($resultado===FALSE)?NULL:$resultado;
		}
	}
}

/* End of file User.php */
/* Location: ./application/libraries/coleccionesApi/get/User.php */