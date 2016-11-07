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
		<!-- CONTINENTES -->
		<div class="col s12">
			<blockquote class="row s12 m12">
			 	<div class="col s12">
			 		<h5>
			 			Continentes <span class="myBadge">{{lstContinente.length}}</span>
				 		<button type="button" class="btn-floating btn-large waves-effect right waves modal-trigger" 
				 			href="#mdlContinente" ng-click="continente=''">
				 			<i class="material-icons">add</i>
				 		</button>
			 		</h5>
			 	</div>
			</blockquote>
			<div class="col offset-m1 m4 s12">
				<select class="browser-default" ng-model="idContinente">
					<option value="" selected>Ninguno</option>
					<option value="{{item.idContinente}}" ng-repeat="item in lstContinente">{{item.continente}}</option>
				</select>
			</div>
		</div>
		<div class="hr"></div>

		<!-- PAISES -->
		<div class="col s12">
			<blockquote class="row s12 m12">
			 	<div class="col s12">
			 		<h5>
			 			Paises <span class="myBadge">{{lstPais.length}}</span>
				 		<button type="button" class="btn-floating btn-large waves-effect right waves modal-trigger" href="#mdlPais" ng-click="newPais={}" ng-disabled="!(idContinente>0)">
				 			<i class="material-icons">add</i>
				 		</button>
			 		</h5>
			 	</div>
			</blockquote>
			<div class="col s12 m3 offset-m1">
				<select class="browser-default" ng-model="codigoPais">
					<option value="" selected>Ninguno</option>
					<option value="{{item.codigoPais}}" ng-repeat="item in lstPais">{{item.pais}}</option>
				</select>
			</div>
			<div class="col s12 m7" ng-show="codigoPais>0">
				<b>Código » </b>{{pais.codigoPais}}
				<b>Pais » </b>{{pais.pais}}
				<b>Nacionalidad » </b>{{pais.nacionalidad}}
			</div>
		</div>
		<div class="hr"></div>

		<!-- CIUDADES -->
		<div class="col s12">
			<blockquote class="row s12 m12">
			 	<div class="col s12">
			 		<h5>
			 			Ciudades <span class="myBadge">{{lstCiudad.length}}</span>
				 		<button type="button" class="btn-floating btn-large waves-effect right waves modal-trigger" href="#mdlCiudad" ng-disabled="!(codigoPais>0)" ng-click="newCiudad=''">
				 			<i class="material-icons">add</i>
				 		</button>
			 		</h5>
			 	</div>
			</blockquote>
			<div class="col s12">
				<table class="striped">
					<thead>
						<tr>
							<th>Id Ciudad</th>
							<th>Ciudad</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="item in lstCiudad">
							<td>{{item.idCiudad}}</td>
							<td>{{item.ciudad}}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


<!-- Modal Continente -->
<div id="mdlContinente" class="modal bottom-sheet">
	<div class="modal-content">
		<h4>
			<span class="material-icons">add_circle</span> Agregar Continente
		</h4>
		<div class="divider"></div>
		<div class="col s12">
			<form class="col s12">
				<div class="input-field"> 
					<div class="form-group">
						<label class="control-label">Continente</label>
						<input type="text" class="form-control" ng-model="newPais.codigoPais">
					</div>
				</div>
			</form>			
		</div>
	</div>
	<div class="modal-footer">
		<button class="waves-effect green btn left" ng-click="guardarContinente()">Guardar</button>
		<button class="waves-effect btn-flat grey lighten-3 right modal-action modal-close">Salir</button>
	</div>
</div>

<!-- Modal Pais -->
<div id="mdlPais" class="modal bottom-sheet">
	<div class="modal-content">
		<h4>
			<span class="material-icons">add_circle</span> Agregar Pais ({{$parent.getItem(lstContinente, 'idContinente', idContinente).continente}})
		</h4>
		<div class="divider"></div>
		<form class="col s12">
			<div class="input-field"> 
				<div class="form-group">
					<label class="control-label">Código de Pais</label>
					<input type="number" class="form-control" ng-model="newPais.codigoPais">
				</div>
			</div>
			<div class="input-field"> 
				<div class="form-group">
					<label class="control-label">Pais</label>
					<input type="text" class="form-control" ng-model="newPais.pais">
				</div>
			</div>
			<div class="input-field"> 
				<div class="form-group">
					<label class="control-label">Nacionalidad</label>
					<input type="text" class="form-control" ng-model="newPais.nacionalidad">
				</div>
			</div>
		</form>	
	</div>
	<div class="modal-footer">
		<button class="waves-effect green btn left" ng-click="guardarPais()">Guardar</button>
		<button class="waves-effect btn-flat grey lighten-3 right modal-action modal-close">Salir</button>
	</div>
</div>

<!-- Modal Ciudad -->
<div id="mdlCiudad" class="modal bottom-sheet">
	<div class="modal-content">
		<h4>
			<span class="material-icons">add_circle</span> Agregar Ciudad ({{$parent.getItem(lstPais, 'codigoPais', codigoPais).pais}})
		</h4>
		<div class="divider"></div>
		<div class="col s12">
			<form>
				<div class="input-field"> 
					<div class="form-group">
						<label class="control-label">Ciudad</label>
						<input type="text" class="form-control" ng-model="newCiudad" ng-init="newCiudad=''">
					</div>
				</div>
			</form>			
		</div>
	</div>
	<div class="modal-footer">
		<button class="waves-effect green btn left" ng-click="guardarCiudad()">Guardar</button>
		<button class="waves-effect btn-flat grey lighten-3 right modal-action modal-close">Salir</button>
	</div>
</div>








