<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."libraries/REST_Controller.php";

class Delete extends REST_Controller {

	public function __construct(){
		parent::__construct();
	}

	protected function cargador($coleccion=NULL,$id=0,$deleteParams=NULL){
		if(!is_null($coleccion) AND $id>0 AND !is_null($deleteParams)){
			$this->load->library('coleccionesApi/delete/'.ucfirst($coleccion));
			if( method_exists($this->{$coleccion}, 'index') ){
				return $this->{$coleccion}->index($id,$deleteParams);
			} else { return []; }
		} else { return []; }
	}

	public function index_delete($coleccion,$id){
		if( deveritas_archivo( APPPATH.'libraries/coleccionesApi/delete/'.ucfirst($coleccion).'.php' ) ){
			$info = $this->cargador($coleccion,(int)$id,$this->delete());
			if( count($info)>0 ){
				$this->response($info,REST_Controller::HTTP_OK);
			} else { $this->response(['error'=>'la coleccion: '.$coleccion.' esta vacia o no existe'],REST_Controller::HTTP_NOT_FOUND); }
		} else { $this->response(['error'=>'Coleccion inexistente'],REST_Controller::HTTP_BAD_REQUEST); }
	}//fin index_delete
}

/* End of file Delete.php */
/* Location: ./application/controllers/api/Delete.php */