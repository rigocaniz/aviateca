<?php 
	@session_start();
	include '../class/session.class.php';
	$session = new Session();
	if ( $session->getProfile() != 2 ) {
		echo "<h4 class='red-text text-darken-2'>No Tiene Acceso a este modulo</h4>";
		exit();
	}
?>
<div class="col m12">
	<div class="row">
		<blockquote class="row s12 m12">
			<!-- TITULO -->
			<div class="col m6 s12">
		 		<h5><i class="material-icons">list</i> Reportes</h5>
			</div>
		</blockquote>

		<!-- REPORTE DE RESERVACIONES CANCELADAS -->
		<form class="col s12" method="GET" action="reporte.php" target="_blank">
			<h5>Reporte » Reservaciones canceladas</h5>
			<div class="hr"></div>

			<div class="row">
				<div class="col s12">
					<div class="col m4 s12">
						<b class="col m4 s12">De Fecha</b>
						<div class="col m8 s12">
							<input type="text" class="datepicker" id="deFecha" name="deFecha" value="<?= date('Y-m-01');?>">
						</div>
					</div>
					<div class="col m4 s12">
						<b class="col m4 s12">Para Fecha</b>
						<div class="col m8 s12">
							<input type="text" class="datepicker" id="paraFecha" name="paraFecha" value="<?= date('Y-m-d');?>">
						</div>
					</div>
					<div class="col m4 s12">
						<button type="submit" class="waves-effect cyan darken-1 btn" name="type" value="resCancelados">
							<i class="material-icons left">done</i> Generar Reporte
						</button>
					</div>
				</div>
			</div>
		</form>			

		<!-- REPORTE DE AGENTES CON MAS VUELOS -->
		<form class="col s12" method="GET" action="reporte.php" target="_blank">
			<h5>Reporte » Top Venta Agentes</h5>
			<div class="hr"></div>

			<div class="row">
				<div class="col s12">
					<div class="col m4 s12">
						<b class="col m4 s12">De Fecha</b>
						<div class="col m8 s12">
							<input type="text" class="datepicker" id="deFecha" name="deFecha" value="<?= date('Y-m-01');?>">
						</div>
					</div>
					<div class="col m4 s12">
						<b class="col m4 s12">Para Fecha</b>
						<div class="col m8 s12">
							<input type="text" class="datepicker" id="paraFecha" name="paraFecha" value="<?= date('Y-m-d');?>">
						</div>
					</div>
					<div class="col m4 s12">
						<button type="submit" class="waves-effect cyan darken-1 btn" name="type" value="topAgentes">
							<i class="material-icons left">done</i> Generar Reporte
						</button>
					</div>
				</div>
			</div>
		</form>

		<!-- REPORTE DE INGRESOS -->
		<form class="col s12" method="GET" action="reporte.php" target="_blank">
			<h5>Reporte » Reporte de Ingresos</h5>
			<div class="hr"></div>

			<div class="row">
				<div class="col s12">
					<div class="col m4 s12">
						<b class="col m4 s12">Agrupar Por</b>
						<div class="col m8 s12">
							<select name="agruparPor" class="browser-default">
								<option value="tipoPago">Tipo de Pago</option>
								<option value="agente">Agente de Viaje</option>
							</select>
						</div>
					</div>
					<div class="col m4 s12">
						<b class="col m4 s12">De Fecha</b>
						<div class="col m8 s12">
							<input type="text" class="datepicker" id="deFecha" name="deFecha" value="<?= date('Y-m-01');?>">
						</div>
					</div>
					<div class="col m4 s12">
						<b class="col m4 s12">Para Fecha</b>
						<div class="col m8 s12">
							<input type="text" class="datepicker" id="paraFecha" name="paraFecha" value="<?= date('Y-m-d');?>">
						</div>
					</div>
					<div class="col m4 offset-m1 s12">
						<button type="submit" class="waves-effect cyan darken-1 btn" name="type" value="repIngresos">
							<i class="material-icons left">done</i> Generar Reporte
						</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

