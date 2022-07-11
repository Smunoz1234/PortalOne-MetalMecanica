<?php 
//header('Content-Type: application/json; charset=utf-8');
if(isset($_GET['type'])&&$_GET['type']!=""){
	require_once('conexion.php');
	if($_GET['type']==1){//Consultar si existe el usuario ha agregar
		$Cons="Select Usuario From tbl_Usuarios Where Usuario='".$_GET['Usuario']."'";
		$SQL_Cons=sqlsrv_query($conexion,$Cons);
		$row_Cons=sqlsrv_fetch_array($SQL_Cons);
		if($row_Cons['Usuario']!=""){
			echo "<p class='text-danger'><i class='fa fa-times-circle-o'></i> No disponible</p>";
		}else{
			echo "<p class='text-info'><i class='fa fa-thumbs-up'></i> Disponible</p>";
		}
	}
	elseif($_GET['type']==2){//Activar o Inactivar Usuario
		$Cons="Select Estado From tbl_Usuarios Where ID_Usuario='".$_GET['ID_Usuario']."'";
		$SQL_Cons=sqlsrv_query($conexion,$Cons);
		$row_Cons=sqlsrv_fetch_array($SQL_Cons);
		if($row_Cons['Estado']==1){
			$Upd="Update tbl_Usuarios Set Estado=2 Where ID_Usuario='".$_GET['ID_Usuario']."'";
			$SQL_Upd=sqlsrv_query($conexion,$Upd);
			if($SQL_Upd){
				echo "2";
			}
		}else{
			$Upd="Update tbl_Usuarios Set Estado=1 Where ID_Usuario='".$_GET['ID_Usuario']."'";
			$SQL_Upd=sqlsrv_query($conexion,$Upd);
			if($SQL_Upd){
				echo "1";
			}
		}
	}
	elseif($_GET['type']==3){//Eliminar el archivo cargado por dropzone
		$temp=ObtenerVariable("CarpetaTmp");
		$url = "../".$temp."/".$_SESSION['CodUser']."/";
		$url .= str_replace(" ","_",$_GET['nombre']);
		//$result=array();
		if(file_exists($url)) {
			unlink($url);		
    	}
		//echo json_encode($result);
	}
	elseif($_GET['type']==4){//Eliminar una linea del carrito en la Orden de venta
		if($_GET['edit']==1){
			$Cons="Delete From tbl_OrdenVentaDetalleCarrito Where LineNum='".$_GET['linenum']."' And CardCode='".$_GET['cardcode']."' And Usuario='".$_SESSION['CodUser']."'";
			$SQL_Cons=sqlsrv_query($conexion,$Cons);
			if($SQL_Cons){
				echo "*Ok*";
			}
		}else{
			$Parametros=array(
				"'".$_GET['linenum']."'",
				"'".$_GET['id']."'",
				"'".$_GET['evento']."'"
			);
			$LimpiarOrden=EjecutarSP('sp_tbl_OrdenVentaDetalle_DelLine',$Parametros);
			if($LimpiarOrden){
				echo "*Ok*";
			}
		}
	}
	elseif($_GET['type']==5){//Consultar si ya existe el archivo de productos cargado en esa categoria
		$Cons="Select ID_Producto From uvw_tbl_Productos Where ItemCode='".$_GET['Cod']."' AND ID_CategoriaProductos='".$_GET['Cat']."'";
		$SQL_Cons=sqlsrv_query($conexion,$Cons);
		$row_Cons=sqlsrv_fetch_array($SQL_Cons);
		if($row_Cons['ID_Producto']!=""){
			echo "<div class='alert alert-warning alert-dismissable'><button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button><i class='fa fa-exclamation-triangle'></i> Ya existe un archivo cargado a esta categoría. Si continua, el archivo actual se reemplazará por este archivo nuevo.</div>";
		}else{
			echo "";			
		}
	}
	elseif($_GET['type']==6){//Eliminar una linea del carrito en la Oferta de venta
		if($_GET['edit']==1){
			$Cons="Delete From tbl_OfertaVentaDetalleCarrito Where LineNum='".$_GET['linenum']."' And CardCode='".$_GET['cardcode']."' And Usuario='".$_SESSION['CodUser']."'";
			$SQL_Cons=sqlsrv_query($conexion,$Cons);
			if($SQL_Cons){
				echo "*Ok*";
			}
		}else{
			$Parametros=array(
				"'".$_GET['linenum']."'",
				"'".$_GET['id']."'",
				"'".$_GET['evento']."'"
			);
			$LimpiarOrden=EjecutarSP('sp_tbl_OfertaVentaDetalle_DelLine',$Parametros);
			if($LimpiarOrden){
				echo "*Ok*";
			}
		}
	}
	elseif($_GET['type']==7){//Limpiar carrito cuando estan creando un documento y selecciona el cliente
		//Limpiar salida de lotes
		$ConsLote="Delete From tbl_LotesDocSAP Where CardCode='".$_GET['cardcode']."' And Usuario='".$_SESSION['CodUser']."'";
		$SQL_ConsLote=sqlsrv_query($conexion,$ConsLote);

		//Limpiar salida de seriales
		$ConsSerial="Delete From tbl_SerialesDocSAP Where CardCode='".$_GET['cardcode']."' And Usuario='".$_SESSION['CodUser']."'";
		$SQL_ConsSerial=sqlsrv_query($conexion,$ConsSerial);
		
		if($_GET['objtype']=="23"){//Oferta de venta
			$Cons="Delete From tbl_OfertaVentaDetalleCarrito Where CardCode='".$_GET['cardcode']."' And Usuario='".$_SESSION['CodUser']."'";
			$SQL_Cons=sqlsrv_query($conexion,$Cons);
			if($SQL_Cons){
				echo "*Ok*";
			}
		}elseif($_GET['objtype']=="17"){//Orden de venta
			$Cons="Delete From tbl_OrdenVentaDetalleCarrito Where CardCode='".$_GET['cardcode']."' And Usuario='".$_SESSION['CodUser']."'";
			$SQL_Cons=sqlsrv_query($conexion,$Cons);
			if($SQL_Cons){
				echo "*Ok*";
			}
		}elseif($_GET['objtype']=="15"){//Entrega ventas
			//Limpiar carrito
			$Cons="Delete From tbl_EntregaVentaDetalleCarrito Where CardCode='".$_GET['cardcode']."' And Usuario='".$_SESSION['CodUser']."'";
			$SQL_Cons=sqlsrv_query($conexion,$Cons);
			
			if($SQL_Cons){
				echo "*Ok*";
			}
		}elseif($_GET['objtype']=="16"){//Devolucion ventas
			//Limpiar carrito
			$Cons="Delete From tbl_DevolucionVentaDetalleCarrito Where CardCode='".$_GET['cardcode']."' And Usuario='".$_SESSION['CodUser']."'";
			$SQL_Cons=sqlsrv_query($conexion,$Cons);
			
			if($SQL_Cons){
				echo "*Ok*";
			}
		}elseif($_GET['objtype']=="67"){//Trasferencia de inventario
			//Limpiar carrito
			$Cons="Delete From tbl_TrasladoInventarioDetalleCarrito Where CardCode='".$_GET['cardcode']."' And Usuario='".$_SESSION['CodUser']."'";
			$SQL_Cons=sqlsrv_query($conexion,$Cons);
			
			if($SQL_Cons){
				echo "*Ok*";
			}
		}elseif($_GET['objtype']=="13"){//Factura de venta
			//Limpiar carrito
			$Cons="Delete From tbl_FacturaVentaDetalleCarrito Where CardCode='".$_GET['cardcode']."' And Usuario='".$_SESSION['CodUser']."'";
			$SQL_Cons=sqlsrv_query($conexion,$Cons);
			
			if($SQL_Cons){
				echo "*Ok*";
			}
		}
		
	}
	elseif($_GET['type']==8){//Eliminar una linea del carrito en la Entrega de venta
		if($_GET['edit']==1){
			$Cons="Delete From tbl_EntregaVentaDetalleCarrito Where LineNum='".$_GET['linenum']."' And CardCode='".$_GET['cardcode']."' And Usuario='".$_SESSION['CodUser']."'";
			$SQL_Cons=sqlsrv_query($conexion,$Cons);
			if($SQL_Cons){
				echo "*Ok*";
			}
		}else{
			$Parametros=array(
				"'".$_GET['linenum']."'",
				"'".$_GET['id']."'",
				"'".$_GET['evento']."'"
			);
			$LimpiarOrden=EjecutarSP('sp_tbl_EntregaVentaDetalle_DelLine',$Parametros);
			if($LimpiarOrden){
				echo "*Ok*";
			}
		}
	}
	elseif($_GET['type']==9){//Eliminar una linea del carrito en la Solicitud de salida
		if($_GET['edit']==1){
			$Cons="Delete From tbl_SolicitudSalidaDetalleCarrito Where LineNum='".$_GET['linenum']."' And CardCode='".$_GET['cardcode']."' And Usuario='".$_SESSION['CodUser']."'";
			$SQL_Cons=sqlsrv_query($conexion,$Cons);
			if($SQL_Cons){
				echo "*Ok*";
			}
		}else{
			$Parametros=array(
				"'".$_GET['linenum']."'",
				"'".$_GET['id']."'",
				"'".$_GET['evento']."'"
			);
			$LimpiarOrden=EjecutarSP('sp_tbl_SolicitudSalidaDetalle_DelLine',$Parametros);
			if($LimpiarOrden){
				echo "*Ok*";
			}
		}
	}
	elseif($_GET['type']==10){//Eliminar una linea del carrito en la Salida de inventario
		if($_GET['edit']==1){
			$Cons="Delete From tbl_SalidaInventarioDetalleCarrito Where LineNum='".$_GET['linenum']."' And CardCode='".$_GET['cardcode']."' And Usuario='".$_SESSION['CodUser']."'";
			$SQL_Cons=sqlsrv_query($conexion,$Cons);
			if($SQL_Cons){
				echo "*Ok*";
			}
		}else{
			$Parametros=array(
				"'".$_GET['linenum']."'",
				"'".$_GET['id']."'",
				"'".$_GET['evento']."'"
			);
			$LimpiarOrden=EjecutarSP('sp_tbl_SalidaInventarioDetalle_DelLine',$Parametros);
			if($LimpiarOrden){
				echo "*Ok*";
			}
		}
	}
	elseif($_GET['type']==11){//Agregar cantidades de lotes en los items en documentos de marketing
		if($_GET['edit']==1){//Creando documento
			$Parametros=array(
				"NULL",
				"NULL",
				"'".$_GET['objtype']."'",
				"'".$_GET['linenum']."'",
				"'".$_GET['itemcode']."'",
				"'".base64_decode($_GET['itemname'])."'",
				"'".$_GET['und']."'",
				"'".$_GET['whscode']."'",
				"'".$_GET['distnumber']."'",
				"'".$_GET['sysnumber']."'",
				"'".$_GET['fechavenc']."'",
				"'".$_GET['cant']."'",
				"'".$_GET['cardcode']."'",
				"'".$_SESSION['CodUser']."'",
				"1"
			);
			$SQL=EjecutarSP('sp_tbl_LotesDocSAP',$Parametros);
			if($SQL){
				echo date('h:i:s a');
			}else{
				throw new Exception('Error al insertar la cantidad del lote');
				exit();
			}
		}
	}
	elseif($_GET['type']==12){//Sumar el total de todos los lotes del item especificado
		$Total=SumarTotalLotesEntregar($_GET['itemcode'],$_GET['linenum'],$_GET['whscode'],$_GET['cardcode'],$_GET['objtype'],$_SESSION['CodUser']);
		echo $Total;
	}
	elseif($_GET['type']==13){//Eliminar una linea del carrito en la Traslado de inventario
		if($_GET['edit']==1){
			$Cons="Delete From tbl_TrasladoInventarioDetalleCarrito Where LineNum='".$_GET['linenum']."' And CardCode='".$_GET['cardcode']."' And Usuario='".$_SESSION['CodUser']."'";
			$SQL_Cons=sqlsrv_query($conexion,$Cons);
			if($SQL_Cons){
				echo "*Ok*";
			}
		}else{
			$Parametros=array(
				"'".$_GET['linenum']."'",
				"'".$_GET['id']."'",
				"'".$_GET['evento']."'"
			);
			$LimpiarOrden=EjecutarSP('sp_tbl_TrasladoInventarioDetalle_DelLine',$Parametros);
			if($LimpiarOrden){
				echo "*Ok*";
			}
		}
	}
	elseif($_GET['type']==14){//Agregar al carrito de Facturacion de Orden de servicio
		$Parametros=array(
			"'".$_GET['metodo']."'",
			"'".$_GET['otsel']."'",
			"'".$_GET['vtaopt']."'",
			"'".$_GET['cardcode']."'",
			"'".$_SESSION['CodUser']."'",
			"'".$_GET['grpsuc']."'"
		);
		$SQL=EjecutarSP('sp_tbl_FacturaOTDetalleCarritoInsert',$Parametros,140);
		if($SQL){
			echo date('h:i:s a');
		}else{
			echo 'Error al insertar el Detalle. Procedimiento > type 14';
		}
	}
	elseif($_GET['type']==15){//Eliminar una linea del carrito en la Facturacion de Orden de servicio
		if($_GET['edit']==1){
			$Parametros=array(
				"'".$_GET['linenum']."'",
				"'".$_GET['cardcode']."'",
				"'".$_SESSION['CodUser']."'",
				"'".$_GET['todos']."'"
			);
			$SQL=EjecutarSP('sp_tbl_FacturaOTDetalle_DelLine',$Parametros);
			if($SQL){
				echo "*Ok*";
			}
		}
	}
	elseif($_GET['type']==16){//Consultar si existe el Socio de negocio (por cedula al crear)
		$Cons="Select CodigoCliente From uvw_Sap_tbl_Clientes Where LicTradNum = '".$_GET['id']."'";
		$SQL_Cons=sqlsrv_query($conexion,$Cons);
		$row_Cons=sqlsrv_fetch_array($SQL_Cons);
		if($row_Cons['CodigoCliente']!=""){
			echo '<div class="alert alert-danger"><i class="fa fa-times-circle-o"></i> Este cliente ya está registrado. <a class="alert-link" href="socios_negocios.php?id='.base64_encode($row_Cons['CodigoCliente']).'&tl=1&metod='.base64_encode('4').'" target="_blank">Consultar cliente <i class="fa fa-external-link"></i></a></div>';
		}else{
			echo "";
		}
	}
	elseif($_GET['type']==17){//Eliminar una linea del carrito en la Devolucion de venta
		if($_GET['edit']==1){
			$Cons="Delete From tbl_DevolucionVentaDetalleCarrito Where LineNum='".$_GET['linenum']."' And CardCode='".$_GET['cardcode']."' And Usuario='".$_SESSION['CodUser']."'";
			$SQL_Cons=sqlsrv_query($conexion,$Cons);
			if($SQL_Cons){
				echo "*Ok*";
			}
		}else{
			$Parametros=array(
				"'".$_GET['linenum']."'",
				"'".$_GET['id']."'",
				"'".$_GET['evento']."'"
			);
			$LimpiarOrden=EjecutarSP('sp_tbl_DevolucionVentaDetalle_DelLine',$Parametros);
			if($LimpiarOrden){
				echo "*Ok*";
			}
		}
	}
	elseif($_GET['type']==18){//Agregar cantidades de seriales en los items
		if($_GET['edit']==1){//Creando documento
			$Parametros=array(
				"NULL",
				"NULL",
				"'".$_GET['objtype']."'",
				"'".$_GET['linenum']."'",
				"'".$_GET['itemcode']."'",
				"'".base64_decode($_GET['itemname'])."'",
				"'".$_GET['und']."'",
				"'".$_GET['whscode']."'",
				"'".$_GET['distnumber']."'",
				"'".$_GET['sysnumber']."'",
				"'".$_GET['fechavenc']."'",
				"'".$_GET['fechaadmin']."'",
				"'".$_GET['cant']."'",
				"'".$_GET['cardcode']."'",
				"'".$_SESSION['CodUser']."'",
				"1"
			);
			$SQL=EjecutarSP('sp_tbl_SerialesDocSAP',$Parametros);
			if($SQL){
				echo date('h:i:s a');
			}else{
				throw new Exception('Error al insertar la cantidad del serial');
				exit();
			}
		}
	}
	elseif($_GET['type']==19){//Sumar el total de todos los seriales del item especificado
		$Total=SumarTotalSerialesEntregar($_GET['itemcode'],$_GET['linenum'],$_GET['whscode'],$_GET['cardcode'],$_GET['objtype'],$_SESSION['CodUser']);
		echo $Total;
	}
	elseif($_GET['type']==20){//Eliminar una linea del carrito en la Factura de venta
		if($_GET['edit']==1){
			$Cons="Delete From tbl_FacturaVentaDetalleCarrito Where LineNum='".$_GET['linenum']."' And CardCode='".$_GET['cardcode']."' And Usuario='".$_SESSION['CodUser']."'";
			$SQL_Cons=sqlsrv_query($conexion,$Cons);
			if($SQL_Cons){
				echo "*Ok*";
			}
		}else{
			$Parametros=array(
				"'".$_GET['linenum']."'",
				"'".$_GET['id']."'",
				"'".$_GET['evento']."'"
			);
			$LimpiarOrden=EjecutarSP('sp_tbl_FacturaVentaDetalle_DelLine',$Parametros);
			if($LimpiarOrden){
				echo "*Ok*";
			}
		}
	}
	elseif($_GET['type']==21){//Eliminar una linea en el cronograma de servicios
		$SQL_Del=Eliminar("tbl_ProgramacionOrdenesServicio","ID IN (".$_GET['linenum'].")");
		if($SQL_Del){
			echo "*Ok*";
		}
	}
	elseif($_GET['type']==22){//Agregar item en el cronograma
		$Parametros=array(
			"'".$_GET['cardcode']."'",
			"'".$_GET['idsucursal']."'",
			"'".$_GET['itemcode']."'",
			"'".$_GET['periodo']."'",
			"'".$_GET['frecuencia']."'",
			"'".FormatoFecha($_GET['fechacorte'])."'",
			"'".$_SESSION['User']."'"
		);
		$SQL=EjecutarSP('sp_tbl_ProgramacionOrdenesServicio',$Parametros);
		if($SQL){
			echo date('h:i:s a');
		}else{
			throw new Exception('Error al insertar el nuevo item');
			exit();
		}
	}
	elseif($_GET['type']==23){//Agregar item en las series de FE
		$Parametros=array(
			"'".$_GET['tipodoc']."'",
			"'".$_GET['series']."'",
			"'".$_SESSION['CodUser']."'"
		);
		$SQL=EjecutarSP('sp_tbl_FacturacionElectronica_Series',$Parametros);
		if($SQL){
			echo date('h:i:s a');
		}else{
			throw new Exception('Error al insertar el nuevo item');
			exit();
		}
	}
	elseif($_GET['type']==24){//Eliminar una linea en las series de FE
		$SQL_Del=Eliminar("tbl_FacturacionElectronica_Series","[ID]='".$_GET['linenum']."'");
		if($SQL_Del){
			echo "*Ok*";
		}
	}
	elseif($_GET['type']==25){//Eliminar una linea del carrito en el cierre de OT
		$Parametros=array(
			"'".$_GET['linenum']."'",
			"'".$_GET['tdoc']."'"
		);
		$DeleteOT=EjecutarSP('sp_tbl_CierreOTDetalleCarritoDelLine',$Parametros);
		if($DeleteOT){
			echo "*Ok*";
		}
	}
	elseif($_GET['type']==26){//Eliminar una linea del carrito en la creacion de OT
		$Parametros=array(
			"'".$_GET['linenum']."'"
		);
		$DeleteOT=EjecutarSP('sp_tbl_CreacionProgramaOrdenesServicioDelLine',$Parametros);
		if($DeleteOT){
			echo "*Ok*";
		}
	}
	elseif($_GET['type']==27){//Duplicar una linea en el cronograma de servicios
		$Parametros=array(
			"'".$_GET['linenum']."'"
		);
		$SQL=EjecutarSP('sp_tbl_ProgramacionOrdenesServicioDuplicarLine',$Parametros);
		if($SQL){
			echo "*Ok*";
		}
	}
	elseif($_GET['type']==28){//Eliminar una linea en el cambio de producto
		$SQL_Del=Eliminar("tbl_CambioProductoOrdenVenta","[ID] IN (".$_GET['linenum'].")");
		if($SQL_Del){
			echo "*Ok*";
		}
	}
	elseif($_GET['type']==29){//Eliminar una linea en la gestion de series
		$SQL_Del=Eliminar("tbl_SeriesSucursalesAlmacenes","[ID]='".$_GET['linenum']."'");
		if($SQL_Del){
			echo "*Ok*";
		}
	}
	elseif($_GET['type']==30){//Agregar item a la gestion de series
		$Parametros=array(
			"'".$_GET['tipodoc']."'"
		);
		$SQL=EjecutarSP('sp_tbl_SeriesSucursalesAlmacenes',$Parametros);
		if($SQL){
			echo date('h:i:s a');
		}else{
			throw new Exception('Error al insertar el nuevo item');
			exit();
		}
	}
	elseif($_GET['type']==31){//Insertar o actualizar actividad desde el Programador de rutas
		$Parametros=array(
			"'".$_GET['sptype']."'",
			"'".$_GET['id_actividad']."'",
			"'".$_GET['id_evento']."'",
			"'".$_GET['llamada_servicio']."'",
			"'".$_GET['id_empleadoactividad']."'",
			"'".FormatoFecha($_GET['fechainicio'],$_GET['horainicio'])."'",
			"'".FormatoFecha($_GET['fechafin'],$_GET['horafin'])."'",
			"'".$_SESSION['CodUser']."'",
			"'".$_GET['metodo']."'",
			"'".$_GET['docentry']."'",
			"'".$_GET['id_asuntoactividad']."'",
			"'".$_GET['titulo_actividad']."'",			
			"'".$_GET['comentarios_actividad']."'",
			"'".$_GET['estado']."'",
			"'".$_GET['id_tipoestadoact']."'",
			"'".$_GET['fechainicio_ejecucion']."'",
			"'".$_GET['horainicio_ejecucion']."'",
			"'".$_GET['fechafin_ejecucion']."'",
			"'".$_GET['horafin_ejecucion']."'",
			"'".$_GET['turno_tecnico']."'"			
		);
		$SQL=EjecutarSP('sp_tbl_Actividades_Rutas',$Parametros,131);
		if($SQL){
			if($_GET['sptype']==1){
				$row=sqlsrv_fetch_array($SQL);
				echo $row['ID_Actividad'];
			}else{
				echo "OK";
			}			
		}else{
			throw new Exception('Error al insertar la actividad en la ruta');
			exit();
		}
	}
	elseif($_GET['type']==32){//Eliminar una linea del carrito en la creacion de factura en lote de proyecto
		$SQL_Del=Eliminar("tbl_CreacionFacturasProyectos","[ID] IN (".$_GET['linenum'].")",0,2);
		if($SQL_Del){
			echo "*Ok*";
		}
	}
	elseif($_GET['type']==33){//Eliminar una linea en el despacho en lote
		$SQL_Del=Eliminar("tbl_DespachoLoteDetalleCarrito","[ID] IN (".$_GET['linenum'].")");
		if($SQL_Del){
			echo "*Ok*";
		}
	}
	elseif($_GET['type']==34){//Eliminar una linea en el despacho en lote
		$Parametros=array(
			"'".$_SESSION['CodUser']."'",
			"'".$_GET['edit']."'",
			"'".$_GET['tdoc']."'",
			"'".$_GET['whscode']."'",
			"'".$_GET['linenum']."'",
			"'".$_GET['cardcode']."'",
			"'".$_GET['id']."'",
			"'".$_GET['evento']."'"
		);
		$SQL=EjecutarSP('sp_ConsultarStockArticuloAlmacenUpd',$Parametros,34);
		if($SQL){
			$row=sqlsrv_fetch_array($SQL);
			echo $row['OnHand'];
		}else{
			throw new Exception('Error al actualizar el stock');
			exit();
		}
	}
	elseif($_GET['type']==35){//Insertar las actividades para crearlas en lote en el programador de rutas
		$ParamOT=array(
			"4",
			"'".$_SESSION['CodUser']."'",
			"'".$_GET['id_evento']."'"
		);

		$SQL=EjecutarSP("sp_ConsultarDatosCalendarioRutasOT",$ParamOT);
		
		if($SQL){
			echo "OK";
		}else{
			throw new Exception('Error al insertar las actividades en lote');
			exit();
		}
	}
	elseif($_GET['type']==36){//Eliminar una linea del carrito en la Solicitud de compras
		if($_GET['edit']==1){
			$Cons="Delete From tbl_SolicitudCompraDetalleCarrito Where LineNum='".$_GET['linenum']."' And CardCode='".$_GET['cardcode']."' And Usuario='".$_SESSION['CodUser']."'";
			$SQL_Cons=sqlsrv_query($conexion,$Cons);
			if($SQL_Cons){
				echo "*Ok*";
			}
		}else{
			$Parametros=array(
				"'".$_GET['linenum']."'",
				"'".$_GET['id']."'",
				"'".$_GET['evento']."'"
			);
			$LimpiarOrden=EjecutarSP('sp_tbl_SolicitudCompraDetalle_DelLine',$Parametros);
			if($LimpiarOrden){
				echo "*Ok*";
			}
		}
	}
	elseif($_GET['type']==37){//Eliminar una linea del carrito en la Orden de compras
		if($_GET['edit']==1){
			$Cons="Delete From tbl_OrdenCompraDetalleCarrito Where LineNum='".$_GET['linenum']."' And CardCode='".$_GET['cardcode']."' And Usuario='".$_SESSION['CodUser']."'";
			$SQL_Cons=sqlsrv_query($conexion,$Cons);
			if($SQL_Cons){
				echo "*Ok*";
			}
		}else{
			$Parametros=array(
				"'".$_GET['linenum']."'",
				"'".$_GET['id']."'",
				"'".$_GET['evento']."'"
			);
			$LimpiarOrden=EjecutarSP('sp_tbl_OrdenCompraDetalle_DelLine',$Parametros);
			if($LimpiarOrden){
				echo "*Ok*";
			}
		}
	}
	elseif($_GET['type']==38){//Eliminar una linea del carrito en la Entrada de compras
		if($_GET['edit']==1){
			$Cons="Delete From tbl_EntradaCompraDetalleCarrito Where LineNum='".$_GET['linenum']."' And CardCode='".$_GET['cardcode']."' And Usuario='".$_SESSION['CodUser']."'";
			$SQL_Cons=sqlsrv_query($conexion,$Cons);
			if($SQL_Cons){
				echo "*Ok*";
			}
		}else{
			$Parametros=array(
				"'".$_GET['linenum']."'",
				"'".$_GET['id']."'",
				"'".$_GET['evento']."'"
			);
			$LimpiarOrden=EjecutarSP('sp_tbl_EntradaCompraDetalle_DelLine',$Parametros);
			if($LimpiarOrden){
				echo "*Ok*";
			}
		}
	}
	elseif($_GET['type']==39){//Eliminar una linea del carrito en la Factura de compras
		if($_GET['edit']==1){
			$Cons="Delete From tbl_FacturaCompraDetalleCarrito Where LineNum='".$_GET['linenum']."' And CardCode='".$_GET['cardcode']."' And Usuario='".$_SESSION['CodUser']."'";
			$SQL_Cons=sqlsrv_query($conexion,$Cons);
			if($SQL_Cons){
				echo "*Ok*";
			}
		}else{
			$Parametros=array(
				"'".$_GET['linenum']."'",
				"'".$_GET['id']."'",
				"'".$_GET['evento']."'"
			);
			$LimpiarOrden=EjecutarSP('sp_tbl_FacturaCompraDetalle_DelLine',$Parametros);
			if($LimpiarOrden){
				echo "*Ok*";
			}
		}
	}
	elseif($_GET['type']==40){//Eliminar una linea en la creacion de actividades en lote en el programador
		$SQL_Del=Eliminar("tbl_LlamadasServicios_Rutas_Lote","ID IN (".$_GET['linenum'].")");
		if($SQL_Del){
			echo "*Ok*";
		}
	}
	elseif($_GET['type']==41){//Eliminar un campo de los adicionales de documentos
		$SQL_Del=Eliminar("tbl_CamposAdicionalesDoc","[ID] IN (".$_GET['linenum'].")");
		if($SQL_Del){
			echo "*Ok*";
		}
	}
	elseif($_GET['type']==42){//Duplicar lineas en el asistente de creacion de actividades en lote
		$Parametros=array(
			"'".$_GET['linenum']."'"
		);
		$SQL=EjecutarSP('sp_tbl_LlamadasServicios_Rutas_LoteDuplicarLine',$Parametros);
		if($SQL){
			echo "*Ok*";
		}
	}
	elseif($_GET['type']==43){//Eliminar una linea de la tabla de dosificaciones
		$SQL_Del=Eliminar("tbl_Dosificaciones","[ID]='".$_GET['linenum']."'");
		if($SQL_Del){
			echo "*Ok*";
		}
	}
	elseif($_GET['type']==44){//Eliminar una linea del detalle Lista de materiales
		$Parametros=array(
			"'".$_GET['linenum']."'",
			"'".$_GET['id']."'",
			"'".$_GET['evento']."'"
		);
		$SQL=EjecutarSP('sp_tbl_ListaMaterialesDetalle_DelLine',$Parametros);
		if($SQL){
			echo "*Ok*";
		}
	}
	elseif($_GET['type']==45){//Duplicar una linea del detalle Lista de materiales
		$Parametros=array(
			"'".$_GET['linenum']."'",
			"'".$_GET['id']."'",
			"'".$_GET['evento']."'"
		);
		$SQL=EjecutarSP('sp_tbl_ListaMaterialesDetalle_DuplicarLine',$Parametros);
		if($SQL){
			echo "*Ok*";
		}
	}
	elseif($_GET['type']==46){//Eliminar una linea de la tabla de dosificaciones
		$SQL_Del=Eliminar("tbl_FormatosSAP","[ID]='".$_GET['linenum']."'");
		if($SQL_Del){
			echo "*Ok*";
		}
	}
	elseif($_GET['type']==47){//Eliminar un registro de las plantillas de actividades
		$Parametros=array(
			"'".$_GET['idDetalle']."'",
			"'".$_GET['codPlant']."'",
			"''",
			"'3'"
		);
		$SQL=EjecutarSP('sp_tbl_PlantillaActividades_Detalle',$Parametros);
		if($SQL){
			echo "*Ok*";
		}
	}
	elseif($_GET['type']==48){//Corregir el nombre de la sucursal en el cronograma de servicios
		$Parametros=array(
			"'".$_GET['linenum']."'",
			"'".isset($_GET['idsuc']) ? $_GET['idsuc'] : ""."'"			
		);
		$SQL=EjecutarSP('sp_tbl_ProgramacionOrdenesServicioCorregir',$Parametros);
		if($SQL){
			echo "*Ok*";
		}
	}

	sqlsrv_close($conexion);
}


?>