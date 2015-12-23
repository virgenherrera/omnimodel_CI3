<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."libraries/REST_Controller.php";

class Put extends REST_Controller {

	public function __construct(){
		parent::__construct();
	}

	protected function cargador($coleccion=NULL,$id=0,$putParams=NULL){
		if( !is_null($coleccion) AND $id>0 AND !is_null($putParams)){
			$this->load->library('coleccionesApi/put/'.ucfirst($coleccion));
			if( method_exists($this->{$coleccion}, 'index') ){
				return $this->{$coleccion}->index($id,$putParams);
			} else { return []; }
		} else { return []; }
	}

	public function index_put($coleccion,$id){
		if( deveritas_archivo( APPPATH.'libraries/coleccionesApi/put/'.ucfirst($coleccion).'.php' ) ){
			$info = $this->cargador($coleccion,(int)$id,$this->put());
			if( count($info)>0 ){
				$this->response($info,REST_Controller::HTTP_OK);
			} else{ $this->response(['error'=>'la coleccion: '.$coleccion.' esta vacia o no existe'],REST_Controller::HTTP_NOT_FOUND); }
		} else{ $this->response(['error'=>'Coleccion inexistente'],REST_Controller::HTTP_BAD_REQUEST); }
	}//fin index_put
}

/* End of file Put.php */
/* Location: ./application/controllers/api/Put.php */