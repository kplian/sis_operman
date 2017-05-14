<?php
/**
*@package pXP
*@file gen-MODUniConsEventos.php
*@author  (admin)
*@date 04-05-2017 02:46:39
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODUniConsEventos extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarUniConsEventos(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='gem.ft_uni_cons_eventos_sel';
		$this->transaccion='GM_UCOEVE_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id','int4');
		$this->captura('id_equipo_medicion','int4');
		$this->captura('tipo','varchar');
		$this->captura('atributos','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('geofenceid','int4');
		$this->captura('servertime','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_ai','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarUniConsEventos(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='gem.ft_uni_cons_eventos_ime';
		$this->transaccion='GM_UCOEVE_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_equipo_medicion','id_equipo_medicion','int4');
		$this->setParametro('tipo','tipo','varchar');
		$this->setParametro('atributos','atributos','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('geofenceid','geofenceid','int4');
		$this->setParametro('servertime','servertime','timestamp');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarUniConsEventos(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='gem.ft_uni_cons_eventos_ime';
		$this->transaccion='GM_UCOEVE_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id','id','int4');
		$this->setParametro('id_equipo_medicion','id_equipo_medicion','int4');
		$this->setParametro('tipo','tipo','varchar');
		$this->setParametro('atributos','atributos','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('geofenceid','geofenceid','int4');
		$this->setParametro('servertime','servertime','timestamp');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarUniConsEventos(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='gem.ft_uni_cons_eventos_ime';
		$this->transaccion='GM_UCOEVE_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id','id','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>