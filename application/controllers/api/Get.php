<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."libraries/REST_Controller.php";

class Get extends REST_Controller {

	public function __construct(){
		parent::__construct();
	}

	protected function cargador($coleccion=NULL,$uriString,$getParams){
		if(!is_null($coleccion)){
			$this->load->library('coleccionesApi/get/'.ucfirst($coleccion));
			if( method_exists($this->{$coleccion}, 'index') ){
				return $this->{$coleccion}->index($uriString,$getParams);
			} else { return []; }
		} else { return []; }
	}

	public function index_get($coleccion=NULL,$uriString=NULL){
		if( deveritas_archivo( APPPATH.'libraries/coleccionesApi/get/'.ucfirst($coleccion).'.php' ) ){
			if($campos==='_'){$campos=NULL;}
			$resultado = $this->cargador($coleccion,$uriString,$this->get());
			if(count($resultado)>0){
				$this->response($resultado,REST_Controller::HTTP_OK);
			} else { $this->response(['error'=>'la coleccion: '.$coleccion.' esta vacia o no existe'],REST_Controller::HTTP_NOT_FOUND); }
		} else { $this->response(['error'=>'no existe la coleccion ['.$coleccion.']'],REST_Controller::HTTP_BAD_REQUEST); }
	}//fin del metodo index
}

/* End of file Get.php */
/* Location: ./application/controllers/api/Get.php */