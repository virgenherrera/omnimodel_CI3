<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."libraries/REST_Controller.php";

class Post extends REST_Controller {

	public function __construct(){
		parent::__construct();
	}

	protected function cargador($coleccion=NULL,$postParams=NULL){
		if(!is_null($coleccion)&&!is_null($postParams)){
			$this->load->library('coleccionesApi/post/'.ucfirst($coleccion));
			if( method_exists($this->{$coleccion}, 'index') ){
				return $this->{$coleccion}->index($postParams);
			} else { return []; }
		} else { return []; }
	}

	public function index_post($coleccion){
		if(deveritas_archivo(APPPATH.'libraries/coleccionesApi/post/'.$coleccion.'.php')){
			$id = $this->cargador($coleccion,$this->input->post());
			if(count($id)>0){
				$this->response($id,REST_Controller::HTTP_OK);
			} else{ $this->response(['error'=>'ha ocurrido un error'],REST_Controller::HTTP_NOT_FOUND); }
		} else{ $this->response(['error'=>'no existe la coleccion ['.$coleccion.']'],REST_Controller::HTTP_BAD_REQUEST); }
	}
}

/* End of file Post.php */
/* Location: ./application/controllers/api/Post.php */