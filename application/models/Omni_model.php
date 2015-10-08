<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//Omnimodel V0.3, ahora con validaciones! XD
class Omni_model extends CI_Model{

	public function __contstruct(){
		parent::__construct();
		//$this->load->database(); <=== nota para evitar error cargar esto desde autoload
	}//Fin __construct

  public function _get($query=FALSE){
    try {
      if($query===FALSE){
        //si $query no es array error de no array
        throw new Exception("Error los parametros suministrados no tenian el formato correcto", 01);
      }

      //si $query viene en la version anterior
      //tratando a trabla, capos y queryMods
      if(isset($query['tabla'])){
        $query['from'] = $query['tabla'];
        unset($query['tabla']);
      }
      if(isset($query['campos'])){
        $query['select'] = $query['campos'];
        unset($query['campos']);
      }
      if(isset($query['queryMods'])){
        foreach ($query['queryMods'] as $key => $value) {
          $query[$key] = $value;
        }
      unset($query['queryMods']);
      }

      //definir cual sera el tipo default de entrega, x Default: 'array'
      $tipo_entrega = (isset($query['result']))?$query['result']:'array';
      unset($query['result']);

      //construccion de la consulta
      if( is_array($query) && isset($query['from']) ){
        //si $query es array y tiene definida la tabla ejecutar funcionalidad
        foreach( $query as $queryMod => $mod ){
          switch ($queryMod) {
            case 'select':
              $this->db->select($mod);
            break;

            case 'select_max':
              $this->db->select_max($mod);
            break;
            
            case 'select_min':
              $this->db->select_min($mod);
            break;
            
            case 'select_avg':
              $this->db->select_avg($mod);
            break;
            
            case 'select_sum':
              $this->db->select_sum($mod);
            break;
            
            case 'distinct':
              $this->db->distinct();
            break;
            
            case 'from':
              $this->db->from($mod);
            break;
            
            case 'join':
              foreach($mod as $union){
                if(isset($union['field'])&&isset($union['statement'])&&!isset($union['side'])){
                  $this->db->join($union['field'],$union['statement']);
                }
                elseif(isset($union['field'])&&isset($union['statement'])&&isset($union['side'])){
                  $this->db->join($union['field'],$union['statement'],$union['side']);
                }
                else{
                  continue;
                }
              }//foreach
            break;
            
            case 'where':
              $this->db->where($mod);
            break;
            
            case 'or_where':
              $this->db->or_where($mod);
            break;  
            
            case 'where_in':
              if(is_array($mod['field'])){
                foreach($mod as $campo=>$value){
                  $this->db->where_in($campo,$value);
                }
              }
            break;

            case 'or_where_in':
              if(is_array($mod['field'])){
                foreach($mod as $campo=>$value){
                  $this->db->or_where_in($campo,$value);
                }
              }
            break;
            
            case 'where_not_in':
              if(is_array($mod['field'])){
                foreach($mod as $campo=>$value){
                  $this->db->where_not_in($campo,$value);
                }
              }
            break;
            
            case 'or_where_not_in':
              if(is_array($mod['field'])){
                foreach($mod as $campo=>$value){
                  $this->db->or_where_not_in($campo,$value);
                }
              }
            break;
            
            case 'like':
              if(!isset($mod[0])&&isset($mod['field'])&&isset($mod['match'])){
                if($mod['wild']=='before'||'after'==$mod['wild']){
                  $wildcard = (isset($mod['wild']))?$mod['wild']:'both';
                }
                else{
                  $wildcard= 'both';
                }
                $this->db->like($mod['field'], $mod['match'],$wildcard);
              }
              else{
                continue;
              }
            break;
            
            case 'or_like':
              if(!isset($mod[0])&&isset($mod['field'])&&isset($mod['match'])){
                if($mod['wild']=='before'||'after'==$mod['wild']){
                  $wildcard = (isset($mod['wild']))?$mod['wild']:'both';
                }
                else{
                  $wildcard= 'both';
                }
                $this->db->like($mod['field'], $mod['match'],$wildcard);
              }
              else{
                continue;
              }
            break;
            
            case 'not_like':
              if(!isset($mod[0])&&isset($mod['field'])&&isset($mod['match'])){
                if($mod['wild']=='before'||'after'==$mod['wild']){
                  $wildcard = (isset($mod['wild']))?$mod['wild']:'both';
                }
                else{
                  $wildcard= 'both';
                }
                $this->db->like($mod['field'], $mod['match'],$wildcard);
              }
              else{
                continue;
              }
            break;
            
            case 'or_not_like':
              if(!isset($mod[0])&&isset($mod['field'])&&isset($mod['match'])){
                if($mod['wild']=='before'||'after'==$mod['wild']){
                  $wildcard = (isset($mod['wild']))?$mod['wild']:'both';
                }
                else{
                  $wildcard= 'both';
                }
                $this->db->like($mod['field'], $mod['match'],$wildcard);
              }
              else{
                continue;
              }
            break;
            
            case 'group_by':
              $this->db->group_by($mod);
            break;
            
            case 'distinct':
              if(isset($mod)){
                $this->db->distinct();
              }
              else{
                continue;
              }
            break;
            
            case 'having':
              if(is_string($mod)){
                $this->db->having($mod);
              }
              elseif(is_array($mod)){
                $this->db->having($mod);
              }
              else{
                continue;
              }
            break;

            case 'or_having':
              if(is_string($mod)){
                $this->db->or_having($mod);
              }
              elseif(is_array($mod)){
                $this->db->or_having($mod);
              }
              else{
                continue;
              }
            break;
            
            case 'order_by':
              if(is_string($mod)){
                $this->db->order_by($mod);
              }
              elseif(!isset($mod[0])&&isset($mod['campo'])&&isset($mod['orden'])){
                $this->db->order_by($mod['campo'],$mod['orden']);
              }
              elseif(isset($mod[0])&&!isset($mod['campo'])&&!isset($mod['orden'])){
                $this->db->order_by($mod);
              }
              else{
                continue;
              }
            break;
            
            case 'limit':
              if(isset($mod['limit'])&&isset($mod['offset']))
              $this->db->limit($mod['limit'],$mod['offset']);
              else{
                continue;
              }
            break;
            
            case 'count_all_results':
              if(isset($mod)){
                $this->db->count_all_results();
              }
              else{
                continue;
              }
            break;
            
            case 'count_all':
              if(isset($mod)&&is_string($mod)){
                $this->db->count_all($mod);
              }
              else{
                continue;
              }
            break;
          /*        //Esta seccion queda pendiente de integrar
            case 'group_start':
              $this->db->
            break;
            
            case 'or_group_start':
              $this->db->
            break;
            
            case 'not_group_start':
              $this->db->
            break;
            
            case 'or_not_group_start':
              $this->db->
            break;
            
            case 'group_end':
              $this->db->
            break;
          */
            default:
              continue;
            break;
          }
        }

      }elseif( is_string($query) ){ //<=== OJO esta seccion ya jala no le muevas
        //si la query fue explicita no es necesario otro proceso
        $resultado = $this->db->query($query); 
      }

      //resolver la preferencia de tipo de resultado esperado
      switch ($tipo_entrega) {
        case 'array':
          $resultado = $this->db->get();
          $resultado = $resultado->result_array();
        break;

        case 'object':
          $resultado = $this->db->get();
          $resultado = $resultado->result();
        break;

        case 'row_object':
          $resultado = $this->db->get();
          $resultado = $resultado->row();
        break;

        case 'row_array':
          $resultado = $this->db->get();
          $resultado = $resultado->row_array();
        break;

        case 'compiled':
          $resultado = $this->db->get_compiled_select();
        break;

        case 'by_id':
          $resultado = $this->db->get();
          $resultado = $resultado->result_array();
          $id = $tipo_entrega;
            var_dump($resultado); die();
            foreach($resultado as $key=>$value){
              $resultado[$value.$id] = $value;
              $id++;
            }//foreach
        break;
/*        
        default:
          $resultado = $this->db->get();
          $resultado = $resultado->result_array();
        break; */
      }//fin switch tipo_entrega
    } catch (Exception $e) {  
        $resultado  =   $e->getMessage();
        $resultado .=   $e->getCode();
        $resultado .=   $e->getFile();
        $resultado .=   $e->getLine();
        @$resultado .=   $e->getTrace();
        $resultado .=   $e->getTraceAsString();
    }
    return $resultado;
  }//FIN test_get

  public function _insert($insert=FALSE){
    if($insert===FALSE){
      $resultado = 'Sin suficientes parametros para realizar la insecion';
    }
    elseif(isset($insert['tabla'])){
      //decidir si insert o insert_batch
      if( count( $insert['data']) == 1){
        $resultado = $this->db->insert($insert['tabla'],$insert['data'][0]);
      }
      elseif( count( $insert['data'] >= 2 ) ){
        $resultado = $this->db->insert_batch($insert['tabla'],$insert['data']);
      }
      else{
        $resultado = 'no se recibio informacion que insertar';
      }
    }
    return $resultado;
  }//Fin _insert

  public function _update($update=FALSE){
    if($update === FALSE){
      $resultado = 'Sin suficientes paramatros para la actualizacion';
    }
    elseif( isset( $update['tabla'] ) ){
      //decidir si update o update batch <===ahora prob&&o con switch
      var_dump($update['modo_batch']);
      switch ( $update['modo_batch'] ) {
        case FALSE:
          $this->db->where($update['id_key'],$update['id_value']);
          $resultado = $this->db->update($update['tabla'],$update['data'][0]);
        break;
        case TRUE:
          $resultado = $this->db->update_batch($update['tabla'],$update['data'],$update['where_batch']);
        break;
        default:
          $resultado = 'Imposible actualizar con la informacion proporcionada, verifiquer formato parametros';
        break;
      }
    }
    return $resultado;
  }//FIN _update

}//fin de la clase Onmi_model