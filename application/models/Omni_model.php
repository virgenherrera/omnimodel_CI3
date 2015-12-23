<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Omni_model extends CI_Model {
	private $getAlias =
		[
			['select',      's',    'sel',      'campos'],
			['select_max',    'smx',    'tabla_max'],
			['select_min',    'smn',    'tabla_min'],
			['select_avg',    'svg',    'tabla_pro'],
			['select_sum',    'sum',    'tabla_sum'],
			['distinct',    'dis',    'distinto'],
			['from',      'f',    'de',     'tabla'],
			['join',      'j',    'union',    'juntar'],
			['where',     'w',    'donde', ],
			['or_where',    'wo',   'donde_o'],
			['where_in',    'wi',   'donde_en'],
			['or_where_in',   'owi',    'o_donde_en'],
			['where_not_in',  'wni',    'donde_no_en'],
			['or_where_not_in', 'owni',   'o_donde_no_en'],
			['like',      'l',    'como',     'parecido'],
			['or_like',     'ol',   'o_como',   'o_parecido'],
			['not_like',    'no',   'no_como',    'no_parecido'],
			['or_not_like',   'onl',    'o_no_como',  'o_no_parecido'],
			['group_by',    'gb',   'agrupar',    'grupo_por'],
			['distinct',    'd',    'distinto',   'diferente'],
			['having',      'h',    'teniendo'],
			['or_having',   'oh',   'o_teniendo'],
			['order_by',    'ob',   'oby',      'ordenado_por'],
			['limit',     'li',   'lim',      'limite'],
			['offset',     'of',   'off',      'rebase']
		];

	protected function valid_get($nombre_buscado=FALSE,$busqueda=FALSE){
		if( is_string($nombre_buscado) ){
			foreach ($this->getAlias as $keyy => $y) {
				foreach ($y as $keyx => $x) {
					if( $nombre_buscado === $x ){
						//ya lo encontre!
						$busqueda = TRUE;
						break 2;
					}
					else{
						//no lo encontre -> seguir
						$busqueda = $busqueda;
						continue;
					}
				}//buscax
			}//buscay
			$msg = ($busqueda===TRUE)?['existe'=>$busqueda,'y'=>$keyy,'x'=>$keyx, 'parse'=>$this->getAlias[$keyy][0]]:$busqueda;
		}//fin es cadena
		else{
			$msg = 'Error: esta funcion solo acepta String como tipo de dato';
		}//fin else
		return $msg;
	}//fin valid_get

	public function _get($query=FALSE,$tipo_entrega=FALSE){
		$this->load->database();
		$resultado = FALSE;
		if(is_array($query)){
			//definir cual sera el tipo default de entrega, x Default: 'array'
			if( isset( $query['result'] ) AND $tipo_entrega===FALSE){
				$tipo_entrega = $query['result'];
				unset( $query['result'] );
			}
			elseif( !isset($query['result']) AND is_string($tipo_entrega) ){
				$tipo_entrega = $tipo_entrega;
			}

			//aplicar analisis de  alias a la query
			$new_query = FALSE;
			foreach ($query as $key => $value) {
				$temp_var = $this->valid_get( $key );
				if( $temp_var['existe'] ){
					$new_query[ $temp_var['parse'] ] = $value;
				}
			}
			$query = $new_query;
			unset($new_query);

			//siempre debe haber algo que seleccionar; * x default
			$query['select'] = (isset($query['select']))?$query['select']:'*';

			//construir consulta
			if( isset( $query['select'] )  && isset( $query['from'] ) ){
				foreach ($query as $queryMod => $mod) {
					if( method_exists($this->db, $queryMod) ){
						$this->db->{$queryMod}($mod);
					}
				}
			}//fin constructor de consulta
		}//fin es array
		elseif( is_string($query) ){ //<=== OJO esta seccion ya jala no le muevas
			//si la query fue explicita no es necesario otro proceso
			$resultado = $this->db->query($query); 
		}

		//resolver el tipo de resultado esperado
		switch ($tipo_entrega) {
			case 'array':
				$resultado = (is_string($query))?$resultado:$this->db->get();
				$resultado = ($resultado===FALSE)?FALSE:$resultado->result_array();
			break;

			case 'object':
				$resultado = (is_string($query))?$resultado:$this->db->get();
				$resultado = ($resultado===FALSE)?FALSE:$resultado->result();
			break;

			case 'row_object':
				$resultado = (is_string($query))?$resultado:$this->db->get();
				$resultado = ($resultado===FALSE)?FALSE:$resultado->row();
			break;

			case 'row_array':
				$resultado = (is_string($query))?$resultado:$this->db->get();
				$resultado = ($resultado===FALSE)?FALSE:$resultado->row_array();
			break;

			case 'compiled':
				$resultado = $this->db->get_compiled_select();
			break;

			default:
				$resultado = (is_string($query))?$resultado:$this->db->get();
				$resultado = ($resultado===FALSE)?FALSE:$resultado->result_array();
			break;
		}//fin switch tipo_entrega
		//limpiar el constructor de consultas :)
		$this->db->reset_query()->flush_cache();
		return $resultado;
	}//fin _get()

	public function _insert($insertSet=NULL,$compiled=FALSE){
		$this->load->database();
		if(!is_null($insertSet)){
			$entrega = ($compiled===TRUE)?'get_compiled_insert':'insert';
			if(isset($insertSet['set'][0])){
				$msg = FALSE;
				foreach ($insertSet['set'] as $set) {
					$ciclo = FALSE;
					$ciclo = $this->db->set( $set )->{$entrega}($insertSet['tabla']);
					if( $compiled === TRUE ){
						$msg[] = $ciclo;
					}
					elseif($this->db->affected_rows()===1){ $msg[] =  $this->db->insert_id(); }
					else{ $msg[] =  FALSE; }
				}//finforeach
	        	return $msg;
			}//fin multiupdate
			elseif(!isset($insertSet['set'][0])){
				$msg = FALSE;
				$msg = $this->db->set( $insertSet['set'] )->{$entrega}( $insertSet['tabla'] );
				if( $compiled === TRUE ){ return $msg; }
				elseif( $this->db->affected_rows() === 1 ){ return $this->db->insert_id(); }
				else{ return FALSE; }
			}//fin update simple
			else{ return FALSE; }
		}//fin no es nulo
		else{ return FALSE; }
	}//fin _insert

	public function _update($updateSet=NULL,$compiled=FALSE){
		$this->load->database();
		if(!is_null($updateSet)){
			$entrega = ($compiled===TRUE)?'get_compiled_update':'update';
			if(isset($updateSet['set'][0])&&is_array($updateSet['set'][0]['id'])){
				$msg = FALSE;
				foreach ($updateSet['set'] as $set) {
					$ciclo = FALSE;
					$id = $set['id'];
					unset($set['id']);
					$ciclo = $this->db->set( $set )->where( $id )->{$entrega}($updateSet['tabla']);
					if( $compiled === TRUE ){
						$msg[] = $ciclo;
					}
					elseif($this->db->affected_rows()===1){ $msg[] =  TRUE; }
					else{ $msg[] =  FALSE; }
				}//finforeach
				return $msg;
			}//fin multiupdate
			elseif(!isset($updateSet['set'][0]) && is_array($updateSet['set']['id'])){
				$msg = FALSE;
				$id = $updateSet['set']['id'];
				unset($updateSet['set']['id']);
				$msg = $this->db->set( $updateSet['set'] )->where( $id )->{$entrega}( $updateSet['tabla'] );
				if( $compiled === TRUE ){ return $msg; }
				elseif( $this->db->affected_rows() === 1 ){ return TRUE; }
				else{ return FALSE; }
			}//fin update simple
			else{ return FALSE; }
		}//fin no es nulo
		else{ return FALSE; }
	}//fin _update

	public function _delete($deleteSet=NULL,$compiled=FALSE){
		$this->load->database();
		if(!is_null($deleteSet)){
			$entrega = ($compiled===TRUE)?'get_compiled_delete':'delete';
			if(isset($deleteSet['where'][0]['id'])){
				$msg = FALSE;
				foreach ($deleteSet['where'] as $where) {
					$ciclo = FALSE;
					$ciclo = $this->db->where( $where['id'] )->{$entrega}($deleteSet['tabla']);
					if( $compiled === TRUE ){
						$msg[] = $ciclo;
					}
					elseif($this->db->affected_rows()===1){ $msg[] =  TRUE; }
					else{ $msg[] =  FALSE; }
				}//finforeach
				return $msg;
			}//fin multidelete
			elseif(!isset($deleteSet['where'][0]['id'])){
				$msg = FALSE;
				$msg = $this->db->where( $deleteSet['where'] )->{$entrega}( $deleteSet['tabla'] );
				if( $compiled === TRUE ){ return $msg; }
				elseif( $this->db->affected_rows() === 1 ){ return TRUE; }
				else{ return FALSE; }
			}//fin delete simple
			else{ return FALSE; }
		}//fin no es nulo
		else{ return FALSE; }
	}//fin _delete

	public function Chuck_Norris($inputArray=NULL){
		$this->load->database();
		$this->load->dbforge();
	}

}

/* End of file Omni_model.php */
/* Location: .//C/Users/hugo/Desktop/Omni_model.php */