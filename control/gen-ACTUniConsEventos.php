<?php
/**
*@package pXP
*@file gen-ACTUniConsEventos.php
*@author  (admin)
*@date 04-05-2017 02:46:39
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTUniConsEventos extends ACTbase{    
			
	function listarUniConsEventos(){
		$this->objParam->defecto('ordenacion','id');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODUniConsEventos','listarUniConsEventos');
		} else{
			$this->objFunc=$this->create('MODUniConsEventos');
			
			$this->res=$this->objFunc->listarUniConsEventos($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarUniConsEventos(){
		$this->objFunc=$this->create('MODUniConsEventos');	
		if($this->objParam->insertar('id')){
			$this->res=$this->objFunc->insertarUniConsEventos($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarUniConsEventos($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarUniConsEventos(){
			$this->objFunc=$this->create('MODUniConsEventos');	
		$this->res=$this->objFunc->eliminarUniConsEventos($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>