<div class="col m12">
	<div class="row">
		<blockquote class="row s12 m12">
	 		<h5><i class="material-icons">local_airport</i> Aeropuerto</h5>
		</blockquote>
		<div class="col s12">
			<b class="col m2 s12">Continente</b>
			<div class="col m3 s12">
				<select class="browser-default" ng-model="idContinente">
					<option value="" selected>Ninguno</option>
					<option value="{{item.idContinente}}" ng-repeat="item in lstContinente">{{item.continente}}</option>
				</select>
			</div>
			<b class="col m1 s12">Pais</b>
			<div class="col m3 s12">
				<select class="browser-default" ng-model="codigoPais">
					<option value="" selected>Ninguno</option>
					<option value="{{item.codigoPais}}" ng-repeat="item in lstPais">{{item.pais}}</option>
				</select>
			</div>
		</div>
		<div class="col s12" style="margin-top: 5px">
			<b class="col m2 s12">Ciudad</b>
			<div class="col m3 s12">
				<select class="browser-default" ng-model="idCiudad">
					<option value="" selected>Ninguno</option>
					<option value="{{item.idCiudad}}" ng-repeat="item in lstCiudad">{{item.ciudad}}</option>
				</select>
			</div>
			<div class="col m4 s12">
				<button type="button" class="btn waves-effect right waves modal-trigger" href="#mdlAeropuerto" ng-click="aeropuerto=''" ng-disabled="!(idCiudad>0)">
		 			<i class="material-icons left">add</i> 
		 			Aeropuerto
		 			<i class="material-icons right">local_airport</i>
		 		</button>
			</div>
		</div>
		<div class="hr"></div>

		<!-- AEROPUERTOS -->
		<div class="col s12">
			<table class="striped">
				<thead>
					<tr>
						<th>Id Aeropuerto</th>
						<th>Aeropuerto</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="item in lstAeropuerto">
						<td>{{item.idAeropuerto}}</td>
						<td>{{item.aeropuerto}}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>


<!-- Modal Aeropuerto -->
<div id="mdlAeropuerto" class="modal bottom-sheet">
	<div class="modal-content">
		<h4>
			<span class="material-icons">add_circle</span> Agregar Aeropuerto <i class="material-icons">local_airport</i>
		</h4>
		<div class="divider"></div>
		<div class="col s12">
			<form class="col s12">
				<div class="input-field"> 
					<div class="form-group">
						<label class="control-label">Aeropuerto</label>
						<input type="text" class="form-control" ng-model="aeropuerto">
					</div>
				</div>
			</form>			
		</div>
	</div>
	<div class="modal-footer">
		<button class="waves-effect green btn left" ng-click="guardarAeropuerto()">Guardar</button>
		<button class="waves-effect btn-flat grey lighten-3 right modal-action modal-close">Salir</button>
	</div>
</div>









