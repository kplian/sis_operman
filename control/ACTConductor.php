<?php
/**
*@package pXP
*@file gen-ACTConductor.php
*@author  (admin)
*@date 16-04-2017 21:02:18
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTConductor extends ACTbase{    
			
	function listarConductor(){
		$this->objParam->defecto('ordenacion','id_conductor');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODConductor','listarConductor');
		} else{
			$this->objFunc=$this->create('MODConductor');
			
			$this->res=$this->objFunc->listarConductor($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarConductor(){
		$this->objFunc=$this->create('MODConductor');	
		if($this->objParam->insertar('id_conductor')){
			$this->res=$this->objFunc->insertarConductor($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarConductor($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarConductor(){
			$this->objFunc=$this->create('MODConductor');	
		$this->res=$this->objFunc->eliminarConductor($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>