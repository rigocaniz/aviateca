<?php 
	@session_start();
	include '../class/session.class.php';
	$session = new Session();
	if ( $session->getProfile() != 3 ) {
		echo "<h4 class='red-text text-darken-2'>No Tiene Acceso a este modulo</h4>";
		exit();
	}
?>
<div class="col m12">
	<div class="row">
		<blockquote class="row s12 m12">
		<!-- TITULO -->
			<div class="col m6 s12">
		 		<h5><i class="material-icons">account_circle</i> Usuarios</h5>
			</div>
			<div class="col m6 s12 right-align">
				<button type="button" class="btn tooltip" ng-click="openUsuario()">
					<i class="material-icons left">add</i>
					Usuario
				</button>
			</div>
		</blockquote>

		<!-- AERONAVES -->
		<div class="col s12" style="margin-top: 30px">
			<table class="responsive-table">
				<thead>
					<tr>
						<th>Usuario</th>
						<th>Nombre</th>
						<th>CUI</th>
						<th>Correo</th>
						<th>Estado</th>
						<th>Tipo Usuario</th>
						<th>% Comisión</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="item in lstUsuario">
						<td>{{item.idUsuario}}</td>
						<td>{{item.nombreCompleto}}</td>
						<td>{{item.cui}}</td>
						<td>{{item.correo}}</td>
						<td>{{item.estado}}</td>
						<td>{{item.tipoUsuario}}</td>
						<td>
							<span ng-show="item.idTipoUsuario==1">{{item.porcentajeComision | number:2}}%</span>
						</td>
						<td style="min-width: 50px">
							<button type="button" class="btn min cyan lighten-1 tooltip" data-title="Resetear" 
								ng-click="resetear( item.idUsuario )">
								<i class="material-icons">autorenew</i>
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>


<!-- Modal NUEVO USUARIO -->
<div id="mdlUsuario" class="modal bottom-sheet">
	<div class="modal-content">
		<h4>
			<i class="material-icons">account_circle</i>
			Agregar Usuario
		</h4>
		<div class="hr"></div>
		<div class="col s12">
			<form class="col s12" autocomplete="off">
				<div class="row">
					<b class="col m2 s12">Usuario</b>
					<div class="col m3 s12">
						<input type="text" ng-model="idUsuario" maxlength="15" ng-pattern="/^[a-zA-Z]*$/" 
							class="validate" ng-class="{'invalid':!idUsuario.length}">
					</div>
					<b class="col m2 s12">Tipo Usuario</b>
					<div class="col m3 s12">
						<select class="browser-default" ng-model="idTipoUsuario">
							<option value="{{item.idTipoUsuario}}" ng-repeat="item in lstTipoUsuario">{{item.tipoUsuario}}</option>
						</select>
					</div>
				</div>

				<div class="row">
					<b class="col m2 s12">Nombre</b>
					<div class="col m5 s12">
						<input type="text" ng-model="nombre">
					</div>
					<b class="col m1 s12">CUI</b>
					<div class="col m4 s12">
						<input type="text" ng-model="cui" maxlength="13">
					</div>
				</div>
				<div class="row">
					<b class="col m2 s12">Correo</b>
					<div class="col m4 s12">
						<input type="text" ng-model="correo">
					</div>
					<b class="col m2 s12" ng-show="idTipoUsuario==1">% Comisión</b>
					<div class="col m4 s12" ng-show="idTipoUsuario==1">
						<input type="number" ng-model="porcentajeComision" min="1" max="99">
					</div>
				</div>
			</form>			
		</div>
	</div>
	<div class="modal-footer">
		<button class="waves-effect green btn left" ng-click="guardarUsuario()">
			<i class="material-icons left">done</i>
			Guardar
		</button>
		<button class="waves-effect btn-flat grey lighten-3 right modal-action modal-close">
			<i class="material-icons left">close</i>
			Salir
		</button>
	</div>
</div>

