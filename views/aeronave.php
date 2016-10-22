<div class="col m12">
	<div class="row">
		<blockquote class="row s12 m12">
			<div class="col m6 s12">
		 		<h5><i class="material-icons">local_airport</i> Aeronaves</h5>
			</div>
			<div class="col m6 s12">
	 			<button type="button" class="btn waves-effect right waves" ng-click="newAeronave()">
		 			<i class="material-icons left">add</i>
		 			Aeronave
		 		</button>
			</div>
		</blockquote>
		<!-- AERONAVES -->
		<div class="col s12">
			<table class="responsive-table">
				<thead>
					<tr>
						<th>Aeronave</th>
						<th>Tipo Aeronave</th>
						<th>Estado</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="item in lstAeronave">
						<td>{{item.aeronave}}</td>
						<td>{{item.tipoAeronave}}</td>
						<td>
							<i class="material-icons left" ng-show="item.idEstadoAeronave==1">airplanemode_active</i>
							<i class="material-icons left" ng-show="item.idEstadoAeronave!=1">airplanemode_inactive</i>
							{{item.estadoAeronave}}
						</td>
						<td>
							<button type="button" class="btn blue lighten-1 tooltipped" data-position="top" data-delay="50" 
								data-tooltip="Modificar" ng-click="modAeronave( item )">
								<i class="material-icons">edit</i>
							</button>
							<button type="button" class="btn indigo lighten-1 tooltipped" data-position="top" data-delay="50" 
								data-tooltip="Destinos" ng-click="placeAeronave( item )">
								<i class="material-icons">place</i>
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>


<!-- Modal Aeronave -->
<div id="mdlAeronave" class="modal bottom-sheet">
	<div class="modal-content">
		<h4 ng-show="nuevo">
			<span class="material-icons">add_circle</span> 
			Agregar Aeronave 
			<i class="material-icons">local_airport</i>
		</h4>
		<h4 ng-show="!nuevo">
			<span class="material-icons">edit</span> 
			Modificar Aeronave 
			<i class="material-icons">local_airport</i>
		</h4>
		<div class="divider"></div>
		<div class="col s12">
			<form class="col s12">
				<div class="row" ng-show="!nuevo">
					<div class="input-field col s4 m2">
						<b>Estado Aeronave</b>
					</div>
					<div class="input-field col s8 m4">
						<select ng-model="aeronave.idEstadoAeronave" class="browser-default" >
							<option value="{{item.idEstadoAeronave}}" ng-repeat="item in lstEstadoAeronave">{{item.estadoAeronave}}</option>
						</select>
					</div>
					<div class="input-field col s12 m6">
						<h5>ID: {{aeronave.idAeronave}}</h5>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s4 m2">
						<b>Tipo Aeronave</b>
					</div>
					<div class="input-field col s8 m4">
						<select ng-model="aeronave.idTipoAeronave" class="browser-default" >
							<option value="{{item.idTipoAeronave}}" ng-repeat="item in lstTipoAeronave">{{item.tipoAeronave}}</option>
						</select>
					</div>
					<div class="input-field col s12 m6">
						<div class="form-group">
							<i class="material-icons prefix">local_airport</i>
							<input id="icon_prefix" type="text" class="validate" ng-model="aeronave.aeronave">
							<label for="icon_prefix" class="active">Descripción Aeronave</label>
						</div>
					</div>
				</div>
				<div class="hr"></div>
				<div class="row">
					<div class="col s12">
						<table>
							<thead>
								<tr>
									<th>Clase</th>
									<th>Capacidad</th>
									<th>Precio</th>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="item in aeronaveClases">
									<td><b>{{item.clase}}</b></td>
									<td>
										<input type="number" ng-model="item.capacidad">
									</td>
									<td>
										<input type="number" ng-model="item.precio">
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</form>			
		</div>
	</div>
	<div class="modal-footer">
		<button class="waves-effect green btn left" ng-click="guardarAeronave()">Guardar</button>
		<button class="waves-effect btn-flat grey lighten-3 right modal-action modal-close">Salir</button>
	</div>
</div>

<!-- Modal Aeronave->destino -->
<div id="mdlDestino" class="modal bottom-sheet">
	<div class="modal-content">
		<h4 ng-show="nuevo">
			<span class="material-icons">add_circle</span> 
			Agregar Aeronave 
			<i class="material-icons">local_airport</i>
		</h4>
		<h4 ng-show="!nuevo">
			<span class="material-icons">edit</span> 
			Modificar Aeronave 
			<i class="material-icons">local_airport</i>
		</h4>
		<div class="divider"></div>
		<div class="col s12">
			<form class="col s12">
				<div class="row" ng-show="!nuevo">
					
					<div class="input-field col s12 m6">
						<h5>ID: {{aeronave.idAeronave}}</h5>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12 m6">
						<div class="form-group">
							<i class="material-icons prefix">local_airport</i>
							<input id="icon_prefix" type="text" class="validate" ng-model="aeronave.aeronave">
							<label for="icon_prefix" class="active">Descripción Aeronave</label>
						</div>
					</div>
				</div>
				<div class="hr"></div>
				<div class="row">
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
						<b class="col m2 s12">Aeropuerto</b>
						<div class="col m3 s12">
							<select class="browser-default" ng-model="idAeropuerto">
								<option value="" selected>Ninguno</option>
								<option value="{{item.idAeropuerto}}" ng-repeat="item in lstAeropuerto">{{item.aeropuerto}}</option>
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
				</div>
			</form>			
		</div>
	</div>
	<div class="modal-footer">
		<button class="waves-effect green btn left" ng-click="guardarAeronave()">Guardar</button>
		<button class="waves-effect btn-flat grey lighten-3 right modal-action modal-close">Salir</button>
	</div>
</div>








