<?php 
if(isset($_GET['id'])&$_GET['id']!=""){
require_once("includes/conexion.php");
PermitirAcceso(601);

$SQLDoc=Seleccionar('uvw_Sap_tbl_FacturasCompras','*',"ID_FacturaCompra='".base64_decode($_GET['id'])."' and CardCode='".$_SESSION['CodigoSAPProv']."'");
$rowDoc=sqlsrv_fetch_array($SQLDoc);
	
//Anexos
$SQL_Anexo=Seleccionar('uvw_Sap_tbl_DocumentosSAP_Anexos','*',"AbsEntry='".$rowDoc['IdAnexo']."'");

$SQL=Seleccionar('uvw_Sap_tbl_FacturasComprasDetalle','*',"ID_FacturaCompra='".base64_decode($_GET['id'])."'");	
?>
<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/PlantillaPrincipal.dwt.php" codeOutsideHTMLIsLocked="false" -->

<head>
<?php include_once("includes/cabecera.php"); ?>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Detalle facturas de proveedor | <?php echo NOMBRE_PORTAL;?></title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script>
function MostrarRet(){
	var posicion_x; 
	var posicion_y;  
	posicion_x=(screen.width/2)-(1200/2);  
	posicion_y=(screen.height/2)-(500/2); 
	remote=open('ajx_retenciones_factura.php?id=<?php echo base64_encode($rowDoc['ID_FacturaCompra']);?>&typefact=2','remote',"width=1200,height=300,location=no,scrollbars=yes,menubars=no,toolbars=no,resizable=no,fullscreen=no,directories=no,status=yes,left="+posicion_x+",top="+posicion_y+"");
	remote.focus();
}
</script>
<!-- InstanceEndEditable -->
</head>

<body>

<div id="wrapper">

    <?php include_once("includes/menu.php"); ?>

    <div id="page-wrapper" class="gray-bg">
        <?php include_once("includes/menu_superior.php"); ?>
        <!-- InstanceBeginEditable name="Contenido" -->
        <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-8">
                    <h2>Detalle factura de proveedor</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index1.php">Inicio</a>
                        </li>
                        <li>
                            <a href="#">Proveedores</a>
                        </li>
						<li>
                            <a href="#">Documentos</a>
                        </li>
						<li>
                            <a href="#">Facturas de proveedor</a>
                        </li>
                        <li class="active">
                            <strong>Detalle factura de proveedor</strong>
                        </li>
                    </ol>
                </div>
            </div>
         <div class="wrapper wrapper-content">			 
		  <div class="row">
			  	<div class="col-lg-3">
					<div class="ibox ">
						<div class="ibox-title">
							<h5><span class="font-normal">N??mero interno</span></h5>
						</div>
						<div class="ibox-content">
							<h3 class="no-margins"><?php echo $rowDoc['DocNum'];?></h3>
						</div>
					</div>
				</div>
			  	<div class="col-lg-3">
					<div class="ibox ">
						<div class="ibox-title">
							<h5><span class="font-normal">N??mero de factura</span></h5>
						</div>
						<div class="ibox-content">
							<h3 class="no-margins"><?php echo $rowDoc['NumAtCard']!="" ? $rowDoc['NumAtCard'] : "&nbsp;";?></h3>
						</div>
					</div>
				</div>				
				<div class="col-lg-3">
					<div class="ibox ">
						<div class="ibox-title">
							<h5><span class="font-normal">Entrada de mercanc??as/servicio</span></h5>
						</div>
						<div class="ibox-content">
							<h3 class="no-margins"><?php echo $rowDoc['DocBaseDocNum']!="" ? $rowDoc['DocBaseDocNum'] : "&nbsp;";?></h3>
						</div>
					</div>
				</div>			  	
			</div>
		  <br>
          <div class="row">
           <div class="col-lg-12">
			    <div class="ibox-content">
					<div class="tabs-container">  
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#tab-1"><i class="fa fa-list"></i> Contenido</a></li>
							<li><a data-toggle="tab" href="#tab-2"><i class="fa fa-paperclip"></i> Anexos</a></li>						
						</ul>
						<div class="tab-content">
							<div id="tab-1" class="tab-pane active">
								<div class="table-responsive">
									<table class="table table-bordered table-hover">
										<thead>
											<thead>
												<tr>
													<th>#</th>
													<th>C??digo</th>
													<th>Descripci??n</th>
													<th>Comentarios</th>
													<th>Cantidad</th>
													<th>Unidad</th>
													<th>Precio</th>
													<th>Total</th>
												</tr>
											</thead>
										</thead>
										<tbody>
										   <?php $i=1; 
											while($row=sqlsrv_fetch_array($SQL)){?>
											<tr>
												<td><?php echo $i;?></td>
												<td><?php echo $row['ItemCode'];?></td>
												<td><?php echo $row['ItemName'];?></td>
												<td><?php echo $row['FreeTxt'];?></td>
												<td align="right"><?php echo number_format($row['Quantity'],2);?></td>
												<td><?php echo $row['UnitMsr'];?></td>											
												<td align="right"><?php echo number_format($row['Price'],2);?></td>
												<td align="right"><?php echo number_format($row['LineTotalSinIVA'],2);?></td>
											</tr>
										<?php $i++;}?>
											<tr>
												<td colspan="7" align="right">SUBTOTAL</td>
												<td align="right"><?php echo number_format($rowDoc['SubTotal'],2);?></td>
											</tr>
											<tr>
												<td colspan="7" align="right">IVA</td>
												<td align="right"><?php echo number_format($rowDoc['VatSum'],2);?></td>
											</tr>
											<tr>
												<td colspan="7" align="right"><?php if($rowDoc['WTSum']>0){?><a href="#" onClick="MostrarRet();">RETENCIONES <i class="fa fa-external-link"></i></a><?php }else{?>RETENCIONES<?php }?></td>
												<td align="right"><?php echo number_format($rowDoc['WTSum'],2);?></td>
											</tr>
											<tr>
												<td colspan="7" align="right"><strong>TOTAL</strong></td>
												<td align="right"><strong><?php echo number_format($rowDoc['DocTotal'],2);?></strong></td>
											</tr>
											<tr>
												<td colspan="8"><strong>Comentarios</strong></td>
											</tr>
											<tr>
												<td colspan="8"><?php echo $rowDoc['Comentarios']!="" ? $rowDoc['Comentarios'] : "&nbsp;";?></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div id="tab-2" class="tab-pane">
								<div class="panel-body">
									<?php
										if($rowDoc['IdAnexo']!=0){?>
											<div class="form-group">
												<div class="col-xs-12">
													<?php while($row_Anexo=sqlsrv_fetch_array($SQL_Anexo)){
																$Icon=IconAttach($row_Anexo['FileExt']);?>
														<div class="file-box">
															<div class="file">
																<a href="attachdownload.php?file=<?php echo base64_encode($row_Anexo['AbsEntry']);?>&line=<?php echo base64_encode($row_Anexo['Line']);?>" target="_blank">
																	<div class="icon">
																		<i class="<?php echo $Icon;?>"></i>
																	</div>
																	<div class="file-name">
																		<?php echo $row_Anexo['NombreArchivo'];?>
																		<br/>
																		<small><?php echo $row_Anexo['Fecha'];?></small>
																	</div>
																</a>
															</div>
														</div>
													<?php }?>
												</div>
											</div>
								<?php }else{ echo "<p>Sin anexos.</p>"; }?>
								</div>										   
							</div>
						</div>					
					</div>					
				</div>
			 </div> 
          </div>
		  <br>
		  <div class="row">
			<div class="col-lg-12">
				<div class="ibox-content">
					<?php 
						if(isset($_GET['return'])){
							$return=base64_decode($_GET['pag'])."?".base64_decode($_GET['return']);
						}else{
							$return="prov_facturas_proveedores.php";
						}
					?>
					<a href="<?php echo $return;?>" class="btn btn-outline btn-default"><i class="fa fa-arrow-circle-o-left"></i> Regresar</a>
					<a href="sapdownload.php?id=<?php echo base64_encode('15');?>&type=<?php echo base64_encode('2');?>&DocKey=<?php echo base64_encode($rowDoc['ID_FacturaCompra']);?>&ObType=<?php echo base64_encode('18');?>&IdFrm=<?php echo base64_encode($rowDoc['IdSeries']);?>" target="_blank" class="btn btn-outline btn-success"><i class="fa fa-download"></i> Descargar formato</a>
				</div>
			</div>
		  </div>
        </div>
        <!-- InstanceEndEditable -->
        <?php include_once("includes/footer.php"); ?>

    </div>
</div>
<?php include_once("includes/pie.php"); ?>
<!-- InstanceBeginEditable name="EditRegion4" -->
 <script>
        $(document).ready(function(){

        });

    </script>
<!-- InstanceEndEditable -->
</body>

<!-- InstanceEnd --></html>
<?php sqlsrv_close($conexion);}?>