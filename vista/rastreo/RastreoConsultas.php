<?php
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Ext.define('Phx.vista.RastreoConsultas', {
    extend: 'Ext.util.Observable',
    constructor: function(config){
        Ext.apply(this,config);
        this.callParent(arguments);
        this.panel = Ext.getCmp(this.idContenedor);
        this.createFormPanel();
        this.setEventos();
        this.showMap();
    },
    createFormPanel: function(){
        //Creación de componentes del panel de parámetros
        this.fechaIni = new Ext.form.DateField({
            fieldLabel: 'Desde'
        });
        this.fechaFin = new Ext.form.DateField({
            fieldLabel: 'Hasta'
        });
        //Botones
        this.btnGraficar = new Ext.Button({
            text: 'Generar',
            width: '100%'
        });

        this.btnGraficarRoutes = new Ext.Button({
            text: 'Generar'
        });
        //Componentes
        this.treeVehiculos = new Ext.tree.TreePanel({
            region: 'west',
            scale: 'large',
            singleClickExpand: true,
            rootVisible: true,
            root: new Ext.tree.AsyncTreeNode({
                text: 'Vehiculos',
                expandable: true
            }),
            animate: true,
            singleExpand: true,
            useArrows: true,
            autoScroll: false,
            containerScroll: true,
            forceLayout: true,
            loader: new Ext.tree.TreeLoader({
                url: '../../sis_mantenimiento/control/Localizacion/listarLocalizacionArbRastreo',
                clearOnLoad: true,
                baseParams: {
                    tipo_nodo:'',
                    vista:'loc',
                    id_localizacion:'',
                    node: 'id'
                }
            }),
            containerScroll: true,
            border: false
        });

        //Arbol para rutas
        this.treeVehiculosRoutes = new Ext.tree.TreePanel({
            scale: 'large',
            singleClickExpand: true,
            rootVisible: true,
            root: new Ext.tree.AsyncTreeNode({
                text: 'Vehiculos',
                expandable: true
            }),
            padding: '10 10 10 10',
            animate: true,
            singleExpand: true,
            useArrows: true,
            autoScroll: false,
            containerScroll: true,
            forceLayout: true,
            loader: new Ext.tree.TreeLoader({
                url: '../../sis_mantenimiento/control/Localizacion/listarLocalizacionArbRastreo',
                clearOnLoad: true,
                baseParams: {
                    tipo_nodo:'',
                    vista:'loc',
                    id_localizacion:'',
                    node: 'id'
                }
            }),
            containerScroll: true,
            border: false
        });

        this.treeVehiculos.getLoader().on('loadexception', function(cmp,node,response){
            var resp = Ext.decode(response.responseText).ROOT.detalle;
            Ext.MessageBox.alert(response.status+' - '+response.statusText,resp.mensaje)
        },this);

        this.treeVehiculosRoutes.getLoader().on('loadexception', function(cmp,node,response){
            var resp = Ext.decode(response.responseText).ROOT.detalle;
            Ext.MessageBox.alert(response.status+' - '+response.statusText,resp.mensaje)
        },this);

        //Mapas
        this.panelMapa = new Ext.Panel({  
            padding: '0 0 0 0',
            tbar: this.tb,
            html:'<div id="map-'+this.idContenedor +'"></div>',
            region:'center',
            split: true, 
            layout:  'fit' ,
            title: 'Mapa'
        });

        //Creación del panel de parámetros
        this.viewPort = new Ext.Container({
            layout: 'border',
            items: [
                {
                    region: 'west',
                    title:'Parametros',
                    layout: {
                        type: 'accordion',
                        animate: true
                    },
                    items: [{
                            layout: 'form',
                            title: 'Posicion Actual',
                            items: [
                                this.btnGraficar,
                                this.treeVehiculos
                            ]
                        },

                        
                        {
                            layout: 'form',
                            title: 'Rutas',
                            items: [
                                this.btnGraficarRoutes,
                                this.fechaIni,
                                this.fechaFin,
                                this.treeVehiculosRoutes
                            ]
                        }
                        
                    ],
                    width: 250,
                    minSize: 150,
                    maxSize: 400,
                    collapsible: true,
                    split: true
                },
                this.panelMapa
            ]
        });

        this.panel.add(this.viewPort);
        this.panel.doLayout();
        this.addEvents('init'); 
    },
    setEventos: function(){
        this.btnGraficar.on('click', this.obtenerTreeIds/*, this.dibujar*/,this);
        this.treeVehiculos.loader.on('beforeload', function(treeLoader,node){
            Ext.apply(this.treeVehiculos.loader.baseParams,{
                id_localizacion: node.attributes['id_localizacion'],
                tipo_nodo: node.attributes['tipo_nodo'],
            });
        },this);
        this.treeVehiculosRoutes.loader.on('beforeload', function(treeLoader,node){
            Ext.apply(this.treeVehiculosRoutes.loader.baseParams,{
                id_localizacion: node.attributes['id_localizacion'],
                tipo_nodo: node.attributes['tipo_nodo'],
            });
        },this);
        this.treeVehiculos.on('click', function(node){
            console.log('click - ',node)
        },this);
    },
    showMap: function(){
        this.vectorSource = new ol.source.Vector();
        this.vectorLayer = new ol.layer.Vector({
          source: this.vectorSource
        });
        this.olview = new ol.View({
            center: [0, 0],
            zoom: 2,
            minZoom: 2,
            maxZoom: 20
        }),
        this.map = new ol.Map({
            target: document.getElementById('map-'+this.idContenedor),
            view: this.olview,
            layers: [
                new ol.layer.Tile({
                    style: 'Aerial',
                    source: new ol.source.OSM()
                }),
                this.vectorLayer
            ]
        });

        this.map.getView().setZoom(17);
        this.map.getView().setCenter(ol.proj.fromLonLat([-68.131096, -16.514822]));

        this.map.on('click', function(e){
            console.log('map click',e)
            this.map.forEachFeatureAtPixel(e.pixel, function(feature, layer) {
                console.log('ffff',feature,layer)
            });
        },this);
    },
    obtenerTreeIds: function(){
        //Verifica si hay algun nodo seleccionado
        var selected = this.treeVehiculos.getSelectionModel().selNode;
        if(selected&&!selected.isRoot){
            var obj={};
            if(selected.attributes.id_uni_cons){
                obj.tipo='uc';
                obj.id_uni_cons = selected.attributes.id_uni_cons;
            } else {
                obj.tipo='loc';
                obj.id_localizacion = selected.attributes.id_localizacion;
            }
            this.obtenerGeoData(obj);
        }
    },
    obtenerGeoData: function(obj){
        var today = new Date();
        Phx.CP.loadingShow();
        Ext.Ajax.request({
            url:'../../sis_mantenimiento/control/EquipoMedicion/listarMediciones',
            params: {
                id_localizacion: obj.id_localizacion,
                id_uni_cons: obj.id_uni_cons,
                tipo: obj.tipo,
                fecha_ini: today.format('d-m-Y'),
                fecha_fin: today.format('d-m-Y'),
                solo_un_registro: 'si',
                start:0,
                limit:150,
                sort:'fecha_medicion',
                dir:'ASC'
            },
            success: this.reloadMap,
            failure: function(response,opts){
                Phx.CP.loadingHide();
                var resp = Ext.decode(response.responseText).ROOT.detalle;
                Ext.MessageBox.alert(response.status+' - '+response.statusText,resp.mensaje)
            },
            timeout: this.timeout,
            scope: this
        });
    },
    points: [
        [-68.131096, -16.514822],
        [-68.131155, -16.514339],
        [-68.130651, -16.514051],
        [-68.130597, -16.513516],
        [-68.131134, -16.512745],
        [-68.131499, -16.511222],
        [-68.131692, -16.510554],
        [-68.132572, -16.509258],
        [-68.132378, -16.508393],
        [-68.132872, -16.506706],
        [-68.133087, -16.506861],
        [-68.133923, -16.506418],
        [-68.134170, -16.506737],
        [-68.135179, -16.506737]
    ],
    reloadMap: function(resp,params){
        var data = Ext.decode(resp.responseText);
        this.vectorSource = new ol.source.Vector();
        Phx.CP.loadingHide();
        for(var i=0;i<data.datos.length;i++){
            var aux = [parseFloat(data.datos[i].latitud),parseFloat(data.datos[i].longitud)];
            var feature = new ol.Feature(
              new ol.geom.Point(ol.proj.fromLonLat(aux))
            );

            var iconStyle = new ol.style.Style({
                image: new ol.style.Icon({
                    anchor: [0.5, 46],
                    anchorXUnits: 'fraction',
                    anchorYUnits: 'pixels',
                    opacity: 0.75,
                    src: '//openlayers.org/en/v3.8.2/examples/data/icon.png'
                }),
                text: new ol.style.Text({
                    font: '12px Calibri,sans-serif',
                    fill: new ol.style.Fill({ color: '#000' }),
                    stroke: new ol.style.Stroke({
                        color: '#fff', width: 2
                    }),
                    text: data.datos[i].codigo+' '+data.datos[i].nombre
                })
            });

            feature.setStyle(iconStyle);
            this.vectorSource.addFeature(feature);
        }
        if(data&&data.datos[0]&&data.datos[0].latitud&&data.datos[0].longitud){
            var aux = [parseFloat(data.datos[0].latitud),parseFloat(data.datos[0].longitud)];
            this.map.getView().setZoom(17);
            this.map.getView().setCenter(ol.proj.fromLonLat(aux));    
        }
    },
    addFeatureClick: function(){
        var feature = new ol.Feature(
                new ol.geom.Point(evt.coordinate)
            );
        feature.setStyle(this.iconStyle);
        this.vectorSource.addFeature(feature);
    }
});
</script>