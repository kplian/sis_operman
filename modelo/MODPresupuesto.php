<?php
/**
*@package pXP
*@file gen-MODPresupuesto.php
*@author  (admin)
*@date 12-06-2013 08:25:14
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODPresupuesto extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarPresupuesto(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='gem.ft_presupuesto_sel';
		$this->transaccion='GM_GEPRES_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_presupuesto','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('codigo','varchar');
		$this->captura('gestion','int4');
		$this->captura('nombre','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarPresupuesto(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='gem.ft_presupuesto_ime';
		$this->transaccion='GM_GEPRES_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('gestion','gestion','int4');
		$this->setParametro('nombre','nombre','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarPresupuesto(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='gem.ft_presupuesto_ime';
		$this->transaccion='GM_GEPRES_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_presupuesto','id_presupuesto','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('gestion','gestion','int4');
		$this->setParametro('nombre','nombre','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarPresupuesto(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='gem.ft_presupuesto_ime';
		$this->transaccion='GM_GEPRES_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_presupuesto','id_presupuesto','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	
	function listarPresupuestoPeriodo(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='gem.ft_rep_presupuesto_periodo_sel';
		$this->transaccion='GM_REPPRE_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		$this->tipo_retorno='record';
		//$this->objParam->defecto('ordenacion','id_tipo_sensor_columna');
        //$this->objParam->defecto('dir_ordenacion','asc');
        $this->count=false;
        
		$datos = $this->objParam->getParametro('datos');
		
		//$this->setParametro('datos','datos','varchar');
		$this->setParametro('id_presupuesto','id_presupuesto','integer');	
		$this->setParametro('mes_ini','mes_ini','integer');	
		$this->setParametro('mes_fin','mes_fin','integer');	
		
		$parametros= explode('@',$datos);
		$tamaño = sizeof($parametros);
		
		for($i=0;$i<$tamaño;$i++){
			$parametros_tipo=explode('#',$parametros[$i]);
			if($parametros_tipo[0]!=''){
				$this->captura($parametros_tipo[0],$parametros_tipo[1]);
			}
		}
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		//echo $this->consulta;exit;
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>