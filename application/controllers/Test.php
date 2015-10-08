<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Test extends CI_controller{
	function index(){
		$query = [	'campos' => 'capital, code, code2',
					'tabla'=>	'country',
					'queryMods'=>['where'=>'name = "mexico" OR name = "aruba"'],
					'result' =>	'by_id'
					];
		$data['datos'] = $this->modelo->_get($query);
		
		//Cargar Vista de pruebas
		$this->load->view('test',$data);
	}//fin del metodo index
}//fin de la clase
//fin de la clase