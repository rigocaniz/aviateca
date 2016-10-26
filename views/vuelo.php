<div class="col m12">
	<div class="row">
		<blockquote class="row s12 m12">
			<div class="col m6 s12">
		 		<h5>
		 			<i class="material-icons">local_airport</i> Vuelos
		 			<span class="myBadge">
		 				<span ng-show="idEstadoVuelo==1">
		 					<i class="material-icons">timer</i> Programados
		 				</span>
		 				<span ng-show="idEstadoVuelo==2">
		 					<i class="material-icons">flight_takeoff</i> Viajando
		 					<span class="badge">3</span>
		 				</span>
		 				<span ng-show="idEstadoVuelo==3">
		 					<i class="material-icons">flag</i> Finalizados
		 					<span class="badge">3</span>
		 				</span>
		 				<span ng-show="idEstadoVuelo==5">
		 					<i class="material-icons">error_outline</i> Cancelados
		 					<span class="badge">3</span>
		 				</span>
		 				<span class="badge-in">{{lstVueloAeronave.length}}</span>
		 			</span>
		 		</h5>
			</div>
			<div class="col m6 s12" style="height: 65px">
				<div style="position: relative;right: 70px" class="right">
					<div class="fixed-action-btn horizontal click-to-toggle" style="position: absolute;right: auto;bottom: auto;">
						<a class="btn-floating btn-large">
							<i class="material-icons">menu</i>
						</a>
						<ul>
							<li>
								<a class="btn-floating blue" ng-click="idEstadoVuelo=1">
									<i class="material-icons">timer</i>
								</a>
							</li>
							<li>
								<a class="btn-floating green" ng-click="idEstadoVuelo=2">
									<i class="material-icons">flight_takeoff</i>
								</a>
							</li>
							<li>
								<a class="btn-floating deep-purple darken-1" ng-click="idEstadoVuelo=3">
									<i class="material-icons">flag</i>
								</a>
							</li>
							<li>
								<a class="btn-floating red" ng-click="idEstadoVuelo=5">
									<i class="material-icons">error_outline</i>
								</a>
							</li>
						</ul>
					</div>	
				</div>
			</div>
		</blockquote>

		<!-- AGREGAR VUELO -->
		<div class="col s12 center-align">
			<a class="waves-effect waves-light btn" ng-click="placeAeronave()">
				<i class="material-icons left">add</i> Vuelo
				<i class="material-icons right">flight_takeoff</i>
			</a>
		</div>

		<!-- AERONAVES -->
		<div class="col s12" style="margin-top: 30px">
			<table class="responsive-table">
				<thead>
					<tr>
						<th>Aeropuerto Origen</th>
						<th>Ciudad Origen</th>
						<th>Fecha Salida</th>
						<th>Hora Salida</th>
						<th>Aeropuerto Destino</th>
						<th>Ciudad Destino</th>
						<th>Hora Aterrizaje</th>
						<th>Tipo Aeronave</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="item in lstVueloAeronave">
						<td>{{item.origen}}</td>
						<td>{{item.ciudadOrigen}}, {{item.paisOrigen}}</td>
						<td>{{item.fechaSalida}}</td>
						<td>{{item.horaSalida}}</td>
						<td>{{item.destino}}</td>
						<td>{{item.ciudadDestino}}, {{item.paisDestino}}</td>
						<td>{{item.horaAterrizaje}}</td>
						<td>{{item.tipoAeronave}}</td>
						<td>
							<button type="button" class="btn red lighten-1 tooltip" data-title="Cancelar" ng-click="cancelarViaje( item )">
								<i class="material-icons">delete</i>
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>


<!-- Modal Aeronave->destino -->
<div id="mdlDestino" class="modal bottom-sheet">
	<div class="modal-content">
		<h4>
			<i class="material-icons">local_airport</i>
			Aeronave › Destinos
			<i class="material-icons">place</i> 
		</h4>
		<div class="hr"></div>
		<div class="col s12">
			<form class="col s12">
				<div class="row">
					<b class="col m2 s12">Tipo Aeronave</b>
					<div class="col m3 s12">
						<select class="browser-default" ng-model="idTipoAeronave">
							<option value="{{item.idTipoAeronave}}" ng-repeat="item in lstTipoAeronave">{{item.tipoAeronave}}</option>
						</select>
					</div>
					<b class="col m2 s12">Aeronave</b>
					<div class="col m3 s12">
						<select class="browser-default" ng-model="idAeronave">
							<option value="{{item.idAeronave}}" ng-repeat="item in lstAeronave">{{item.aeronave}}</option>
						</select>
					</div>
					<div class="col m2">
						<i class="material-icons left" ng-show="lstAeronave.length===0">airplanemode_inactive</i>
					</div>
				</div>

				<!-- NUEVO DESTINO -->
				<div class="row">
					<!-- ORIGEN -->
					<div class="legend">
						<div class="titulo">
							<i class="material-icons left">flight_takeoff</i>
							Origen
						</div>
					</div>
					<div class="col s12">
						<b class="col m2 s12">Continente</b>
						<div class="col m3 s12">
							<select class="browser-default" ng-model="idContinenteOrigen">
								<option value="{{item.idContinente}}" ng-repeat="item in lstContinente">{{item.continente}}</option>
							</select>
						</div>
						<b class="col m2 s12">Pais</b>
						<div class="col m3 s12">
							<select class="browser-default" ng-model="codigoPaisOrigen">
								<option value="{{item.codigoPais}}" ng-repeat="item in lstPaisOrigen">{{item.pais}}</option>
							</select>
						</div>
					</div>
					<div class="col s12" style="margin-top: 5px">
						<b class="col m2 s12">Ciudad</b>
						<div class="col m3 s12">
							<select class="browser-default" ng-model="idCiudadOrigen">
								<option value="{{item.idCiudad}}" ng-repeat="item in lstCiudadOrigen">{{item.ciudad}}</option>
							</select>
						</div>
						<b class="col m2 s12">Aeropuerto Origen</b>
						<div class="col m3 s12">
							<select class="browser-default" ng-model="idAeropuertoOrigen">
								<option value="{{item.idAeropuerto}}" ng-repeat="item in lstAeropuertoOrigen">{{item.aeropuerto}}</option>
							</select>
						</div>
					</div>
					<div class="col s12">
						<b class="col m2 s4">Hora</b>
						<div class="col m2 s4">
							<select class="browser-default" ng-model="horaOrigen">
								<option value="{{item}}" ng-repeat="item in lstHora">{{item}}</option>
							</select>
						</div>
						<div class="col m2 s4">
							<select class="browser-default" ng-model="minutoOrigen">
								<option value="{{item}}" ng-repeat="item in lstMinuto">{{item}}</option>
							</select>
						</div>
						<b class="col m1 s4">Fecha</b>
						<div class="col m4 s8">
							<input type="date" class="datepicker" id="deFecha">
						</div>
					</div>

					<!-- DESTINO -->
					<div class="legend">
						<div class="titulo">
							<i class="material-icons left">flight_land</i>
							Destino
						</div>
					</div>
					<div class="col s12">
						<b class="col m2 s12">Continente</b>
						<div class="col m3 s12">
							<select class="browser-default" ng-model="idContinenteDestino">
								<option value="{{item.idContinente}}" ng-repeat="item in lstContinente">{{item.continente}}</option>
							</select>
						</div>
						<b class="col m2 s12">Pais</b>
						<div class="col m3 s12">
							<select class="browser-default" ng-model="codigoPaisDestino">
								<option value="{{item.codigoPais}}" ng-repeat="item in lstPaisDestino">{{item.pais}}</option>
							</select>
						</div>
					</div>
					<div class="col s12" style="margin-top: 5px">
						<b class="col m2 s12">Ciudad</b>
						<div class="col m3 s12">
							<select class="browser-default" ng-model="idCiudadDestino">
								<option value="{{item.idCiudad}}" ng-repeat="item in lstCiudadDestino">{{item.ciudad}}</option>
							</select>
						</div>
						<b class="col m2 s12">Aeropuerto Origen</b>
						<div class="col m3 s12">
							<select class="browser-default" ng-model="idAeropuertoDestino">
								<option value="{{item.idAeropuerto}}" ng-repeat="item in lstAeropuertoDestino">{{item.aeropuerto}}</option>
							</select>
						</div>
					</div>
					<div class="col s12">
						<b class="col m2 s4">Hora</b>
						<div class="col m2 s4">
							<select class="browser-default" ng-model="horaDestino">
								<option value="{{item}}" ng-repeat="item in lstHora">{{item}}</option>
							</select>
						</div>
						<div class="col m2 s4">
							<select class="browser-default" ng-model="minutoDestino">
								<option value="{{item}}" ng-repeat="item in lstMinuto">{{item}}</option>
							</select>
						</div>
						<b class="col m1 s4">Fecha</b>
						<div class="col m4 s8">
							<input type="date" class="datepicker" id="paraFecha">
						</div>
					</div>
				</div>
			</form>			
		</div>
	</div>
	<div class="modal-footer">
		<button class="waves-effect green btn left" ng-click="guardarDestinoAeronave()">
			<i class="material-icons left">done</i>
			Guardar
		</button>
		<button class="waves-effect btn-flat grey lighten-3 right modal-action modal-close">
			<i class="material-icons left">close</i>
			Salir
		</button>
	</div>
</div>









