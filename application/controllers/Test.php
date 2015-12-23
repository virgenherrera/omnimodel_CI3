<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Test extends CI_controller{

	function __construct(){
		parent::__construct();
		$this->load->model('omni_model','modelo');
	}

	function index(){
		//Consulta numero: 1
		$query = [	's' => 'capital as pera, code, code2',
					'f'=>	'country',
					'w'=>'name = "mexico" OR name = "aruba"',
					'result' =>	'compiled'
					];
		$data['querys'][] = $this->modelo->_get($query);

		//Consulta numero: 2
		$query = [//	'campos' => 'capital, code, code2',
					'f'=>	'country',
					'w'=>'name = "mexico" OR name = "aruba"',
					'result' =>	'compiled' //ojo se declara un  tipo de retorno
					];
		$data['querys'][] = $this->modelo->_get($query,'object'); //pero el del segundo parametro mayor prioridad

		//Consulta numero: 3
		$query = [	'from'=>	'country',
					'result'=>'row_array'
					];
		$data['querys'][] = $this->modelo->_get($query);		

		//Consulta numero: 4
		$query = [	'tabla'=>	'country'];
		$data['querys'][] = $this->modelo->_get($query,'row_object');

		//Consulta numero: 5
		$query = [	'f'=>	'country'];
		$data['querys'][] = $this->modelo->_get($query,'compiled');

		//Cargar Vista de pruebas
		$this->load->view('test',$data);
	}//fin del metodo index

	function forge(){

		$dbutilMethods = 
			[
				'list_databases',
				'database_exists',
				'optimize_table',
				'optimize_database',
				'repair_table',
				'csv_from_result',
				'xml_from_result',
				'backup',
			];
		$this->load->dbutil();
		//print_array( get_class_methods($this->dbutil) );
		echo '<hr>';

		$dbforgeMethods =
			[
				'create_database',
				'drop_database',
				'add_key',
				'add_field',
				'create_table',
				'drop_table',
				'rename_table',
				'add_column',
				'drop_column',
				'modify_column',
			];
		$this->load->dbforge();
		//print_array( get_class_methods($this->dbforge) );
		echo '<hr>';

		$dbMethods = 
			[
				'db_connect',
				'reconnect',
				'db_select',
				'version',
				'trans_begin',
				'trans_commit',
				'trans_rollback',
				'affected_rows',
				'insert_id',
				'field_data',
				'error',
				'select',
				'select_max',
				'select_min',
				'select_avg',
				'select_sum',
				'distinct',
				'from',
				'join',
				'where',
				'or_where',
				'where_in',
				'or_where_in',
				'where_not_in',
				'or_where_not_in',
				'like',
				'not_like',
				'or_like',
				'or_not_like',
				'group_start',
				'or_group_start',
				'not_group_start',
				'or_not_group_start',
				'group_end',
				'group_by',
				'having',
				'or_having',
				'order_by',
				'limit',
				'offset',
				'set',
				'get_compiled_select',
				'get',
				'count_all_results',
				'get_where',
				'insert_batch',
				'set_insert_batch',
				'get_compiled_insert',
				'insert',
				'replace',
				'get_compiled_update',
				'update',
				'update_batch',
				'set_update_batch',
				'empty_table',
				'truncate',
				'get_compiled_delete',
				'delete',
				'dbprefix',
				'set_dbprefix',
				'start_cache',
				'stop_cache',
				'flush_cache',
				'reset_query',
				'__construct',
				'initialize',
				'db_pconnect',
				'db_set_charset',
				'platform',
				'query',
				'load_rdriver',
				'simple_query',
				'trans_off',
				'trans_strict',
				'trans_start',
				'trans_complete',
				'trans_status',
				'compile_binds',
				'is_write_type',
				'elapsed_time',
				'total_queries',
				'last_query',
				'escape',
				'escape_str',
				'escape_like_str',
				'primary',
				'count_all',
				'list_tables',
				'table_exists',
				'list_fields',
				'field_exists',
				'escape_identifiers',
				'insert_string',
				'update_string',
				'call_function',
				'cache_set_path',
				'cache_on',
				'cache_off',
				'cache_delete',
				'cache_delete_all',
				'close',
				'display_error',
				'protect_identifiers',
			];
		foreach(array_merge($dbutilMethods,$dbforgeMethods,$dbMethods) as $metodo){
			if( method_exists($this->dbutil, $metodo) ){
				echo 'existo en dbutil <br>';
			}
			elseif( method_exists($this->dbforge, $metodo) ){
				echo 'existo en dbforge <br>';
			}
			elseif( method_exists($this->db, $metodo) ){
				echo 'existo en db <br>';
			}
			else{
				echo 'NO existo en nigunab <br>';
			}
		}

		//print_array( get_class_methods($this->db) );
	}
}//fin de la clase
//fin de la clase