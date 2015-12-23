<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login{
	protected $ci;

	public function __construct(){
        $this->ci =& get_instance();
	}

	public function index($postParams){
		if(!empty($postParams['cliente'])AND!empty($postParams['password'])){
			$query['f'] = 'user';
			if(	filter_var($postParams['cliente'],FILTER_VALIDATE_EMAIL)){$query['w'] = ['email'	=>$postParams['cliente'],'password'=>md5($postParams['password'])]; }
			if(!filter_var($postParams['cliente'],FILTER_VALIDATE_EMAIL)){$query['w'] = ['usuario'	=>$postParams['cliente'],'password'=>md5($postParams['password'])]; }

			$query	=	$this->ci->modelo->_gett($query);
			if( count($query)===1 ){
				
				$session_data = $query[0];
				$session_data['status'] = 'Loggeado';
				$session_data['c'] = $query[0]['id_user'];
				$this->ci->session->set_userdata('Loggeado',$session_data);
				
				return ['seLogeo'=>TRUE,'c'=>$query[0]['id_user']];
			}
			else{ return ['seLogeo'=>FALSE]; }
		} else { return []; }
	}

	

}

/* End of file Login.php */
/* Location: ./application/libraries/coleccionesApi/post/Login.php */