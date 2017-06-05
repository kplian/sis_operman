<?php
	header("content-type: text/javascript; charset=UTF-8");
?>
<script>
	Ext.define('Phx.vista.RastreoConsultas', {
        extend: 'Ext.util.Observable',
		//viewport: {},

		constructor: function(config){
            var port = new Ext.Panel({
                id: this.idContenedor+'_main_panel_grid',
                region:'center',
                layout: 'border',
                items:[{
                    region: 'center',
                    title: 'Main',
                    items: [
                        new Ext.list.ListView({
                                id: 'af_filter_depto_cbo',
                                scope: this,
                                store: new Ext.data.JsonStore({
                                    url: '../../sis_parametros/control/Depto/listarDeptoFiltradoDeptoUsuario',
                                    id: 'id_depto',
                                    root: 'datos',
                                    fields: ['id_depto','codigo','nombre'],
                                    totalProperty: 'total',
                                    sortInfo: {
                                        field: 'codigo',
                                        direction: 'ASC'
                                    },
                                    baseParams:{
                                        start: 0,
                                        limit: 10,
                                        sort: 'codigo',
                                        dir: 'ASC',
                                        modulo: 'KAF',
                                        par_filtro:'DEPPTO.codigo#DEPPTO.nombre'
                                    }
                                }),
                                singleSelect: true,
                                emptyText: 'No existen departamentos habilitados',
                                reserveScrollOffset: true,
                                columns: [{
                                    //header: 'id_depto',
                                    width: 0.01,
                                    dataIndex: 'id_depto',
                                    hidden: true
                                },{
                                    header: 'Código',
                                    width: .3,
                                    dataIndex: 'codigo'
                                },{
                                    header: 'Nombre',
                                    width: .6, 
                                    dataIndex: 'nombre'
                                }]
                            })
                      
                    ]
                }] 
            });
		},

		createLayout: function(){
			console.log('asdasd');
            new Ext.Viewport({
                layout: 'border',
                items: [{
                    region: 'north',
                    html: '<h1 class="x-panel-header">Page Title</h1>',
                    autoHeight: true,
                    border: false,
                    margins: '0 0 5 0'
                }, {
                    region: 'west',
                    collapsible: true,
                    title: 'Navigation',
                    width: 200
                    // the west region might typically utilize a TreePanel or a Panel with Accordion layout
                }, {
                    region: 'south',
                    title: 'Title for Panel',
                    collapsible: true,
                    html: 'Information goes here',
                    split: true,
                    height: 100,
                    minHeight: 100
                }, {
                    region: 'east',
                    title: 'Title for the Grid Panel',
                    collapsible: true,
                    split: true,
                    width: 200,
                    xtype: 'grid',
                    // remaining grid configuration not shown ...
                    // notice that the GridPanel is added directly as the region
                    // it is not "overnested" inside another Panel
                }, {
                    region: 'center',
                    xtype: 'tabpanel', // TabPanel itself has no title
                    items: {
                        title: 'Default Tab',
                        html: 'The first tab\'s content. Others may be added dynamically'
                    }
                }]
            });
		}

	})
</script>