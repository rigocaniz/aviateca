<div class="col m12">
	<div class="row">
		<blockquote class="row s12 m12">
		<!-- TITULO -->
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
		 					<i class="material-icons">flight_land</i> Finalizados
		 					<span class="badge">3</span>
		 				</span>
		 				<span ng-show="idEstadoVuelo==5">
		 					<i class="material-icons">close</i> Cancelados
		 					<span class="badge">3</span>
		 				</span>
		 				<span class="badge-in">{{lstVueloAeronave.length}}</span>
		 			</span>
		 		</h5>
			</div>

			<!-- MENU ESTADOS -->
			<div class="col m6 s12" style="height: 65px">
				<div style="position: relative;right: 70px" class="right">
					<div class="fixed-action-btn horizontal" style="position: absolute;right: auto;bottom: auto;">
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
									<i class="material-icons">flight_land</i>
								</a>
							</li>
							<li>
								<a class="btn-floating red" ng-click="idEstadoVuelo=5">
									<i class="material-icons">close</i>
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
						<td style="min-width: 50px">
							<button type="button" class="btn min cyan lighten-1 tooltip" data-title="Detalle" 
								ng-click="openDetalle( item )">
								<i class="material-icons">info</i>
							</button>
							<button type="button" class="btn min grey darken-2 tooltip" data-title="Incidente" 
								ng-click="openIncidente( item )" ng-show="item.idEstadoVuelo!=5">
								<i class="material-icons">announcement</i>
							</button>
							<button type="button" class="btn min green lighten-1 tooltip" data-title="Viajar" 
								ng-click="openCambiarEstadoVuelo( 2, item.idVuelo, $index )" ng-show="item.idEstadoVuelo==1">
								<i class="material-icons">flight_takeoff</i>
							</button>
							<button type="button" class="btn min deep-purple darken-1 tooltip" data-title="Aterrizar" 
								ng-click="openCambiarEstadoVuelo( 3, item.idVuelo, $index )" ng-show="item.idEstadoVuelo==2">
								<i class="material-icons">flight_land</i>
							</button>
							<button type="button" class="btn min red lighten-1 tooltip" data-title="Cancelar" 
								ng-click="openCambiarEstadoVuelo( 5, item.idVuelo, $index )" ng-show="item.idEstadoVuelo==1">
								<i class="material-icons">close</i>
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
			Agregar Vuelo
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

				<!-- PRECIO BOLETO -->
				<div class="row">
					<div class="col m6 s12" ng-repeat="item in lstClasePrecio">
						<b class="col s5">$ Clase {{item.clase}}</b>
						<div class="col s7">
							<input type="number" ng-model="item.precio" min="0">
						</div>
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

<!-- Modal CAMBIAR ESTADO -->
<div id="mdlEstadoVuelo" class="modal bottom-sheet">
	<div class="modal-content">
		<h4>
			Cambiar Estado Vuelo: 
			<b>
				<span ng-show="vuelo.idEstadoVuelo==2">
					Viajar <i class="material-icons">flight_takeoff</i>
				</span>
				<span ng-show="vuelo.idEstadoVuelo==3">
					Aterrizar <i class="material-icons">flight_land</i>
				</span>
				<span ng-show="vuelo.idEstadoVuelo==5">
					Cancelar <i class="material-icons">close</i>
				</span>
			</b>
		</h4>
		<div class="hr"></div>
		<div class="col s12">
			<form class="col s12">
				<div class="row">
					<b class="col m2 s12">Comentario</b>
					<div class="col m10 s12">
						<textarea ng-model="vuelo.comentario" class="myTextarea" rows="5"></textarea>
					</div>
				</div>
			</form>			
		</div>
	</div>
	<div class="modal-footer">
		<button class="waves-effect green btn left" ng-click="cambiarEstadoVuelo()">
			<i class="material-icons left">done</i>
			Confirmar
		</button>
		<button class="waves-effect btn-flat grey lighten-3 right modal-action modal-close">
			<i class="material-icons left">close</i>
			Salir
		</button>
	</div>
</div>

<!-- Modal INCIDENTES -->
<div id="mdlIncidente" class="modal bottom-sheet">
	<div class="modal-content">
		<h4>
			<i class="material-icons">announcement</i>
			Incidentes
		</h4>
		<div class="hr"></div>
		<div class="col s12" ng-show="!nuevoIncidente">
			<button class="waves-effect green btn left" ng-click="nuevoIncidente=true">
				<i class="material-icons left">add</i>
				Agregar Incidente
			</button>
		</div>
		<div class="col s12" ng-show="nuevoIncidente">
			<div class="row">
				<b class="col m2 s12">Incidente</b>
				<div class="col m10 s12">
					<textarea ng-model="incidente" class="myTextarea" rows="5"></textarea>
				</div>
			</div>
			<div class="row">
				<div class="col offset-m2 m8 s12">
					<button class="waves-effect green btn left" ng-click="guardarIncidente()">
						<i class="material-icons left">done</i>
						Guardar Incidente
					</button>
					<button class="waves-effect btn grey" ng-click="nuevoIncidente=false" style="margin-left: 7px">
						<i class="material-icons left">close</i>
						Cancelar
					</button>
				</div>
			</div>
		</div>

		<!-- LISTA DE INCIDENTES -->
		<div class="col s12" ng-show="!nuevoIncidente">
			<table class="responsive-table">
				<thead>
					<tr>
						<th>Incidente</th>
						<th>Fecha</th>
						<th>Usuario</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="item in lstIncidente">
						<td>{{item.incidente}}</td>
						<td>{{item.fechaHora}}</td>
						<td>{{item.nombreCompleto}}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="modal-footer">
		<button class="waves-effect btn-flat grey lighten-3 right modal-action modal-close">
			<i class="material-icons left">close</i>
			Salir
		</button>
	</div>
</div>

<!-- Modal DETALLE VUELO -->
<div id="mdlDetalle" class="modal bottom-sheet">
	<div class="modal-content">
		<div class="row" style="margin: -15px 0px -7px 0px;">
			<div class="col m4 s12">
				<h5># Vuelo: <b>{{vuelo.idVuelo}}</b></h5>
			</div>
			<div class="col m8 s12 right-align">
				<h5>Aeronave: <b>{{vuelo.aeronave}} ({{vuelo.tipoAeronave}})</b></h5>
			</div>
		</div>
		<div class="hr"></div>
		<div class="row">
			<div class="col s12">Estado: <b>{{vuelo.estadoVuelo}}</b></div>
			<div class="col m6 s12">Tiempo de Viaje Aproximado: <b>{{vuelo.tiempoViaje}}</b></div>
			<div class="col m6 s12 right-align">
				<a ng-href="reporte.php?type=asignacion&idVuelo={{vuelo.idVuelo}}" class="waves-effect btn blue darken-1" target="_blank">
					<i class="material-icons left">format_list_numbered</i>
					Asignación Pasajeros
				</a>
			</div>
		</div>
		
		<!-- DATOS DE ORIGEN -->
		<div class="row" style="margin-top: 15px">
			<div class="legend">
				<div class="titulo">
					<i class="material-icons left">flight_takeoff</i>
					Lugar Origen
				</div>
			</div>
			<div class="col m3 s12">Salida: <b>{{vuelo.fechaSalida}} - {{vuelo.horaSalida}}</b></div>
			<div class="col m9 s12">Aeropuerto: <b>{{vuelo.origen}} - {{vuelo.ciudadOrigen}}, {{vuelo.paisOrigen}}, {{vuelo.continenteOrigen}}</b></div>
		</div>

		<!-- DATOS DE DESTINO -->
		<div class="row" style="margin-top: 15px">
			<div class="legend">
				<div class="titulo">
					<i class="material-icons left">flight_land</i>
					Lugar Destino
				</div>
			</div>
			<div class="col m3 s12">Aterrizaje: <b>{{vuelo.fechaAterrizaje}} - {{vuelo.horaAterrizaje}}</b></div>
			<div class="col m9 s12">Aeropuerto: <b>{{vuelo.destino}} - {{vuelo.ciudadDestino}}, {{vuelo.paisDestino}}, {{vuelo.continenteDestino}}</b></div>
		</div>

		<!-- DISPONIBILIDAD -->
		<div class="row" style="margin-top: 15px">
			<div class="legend">
				<div class="titulo">
					<i class="material-icons left">people</i>
					Disponibilidad
				</div>
			</div>
			<div class="col s12">
				<table class="responsive-table">
					<thead>
						<tr>
							<th>Clase</th>
							<th>Capacidad</th>
							<th># Pasajeros</th>
							<th>Acientos Disponibles</th>
							<th>Precio</th>
							<th>Disponibilidad</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="item in vuelo.lstClase.lstClase">
							<td>{{item.clase}}</td>
							<td>{{item.capacidad}}</td>
							<td>{{item.numeroPasajeros}}</td>
							<td>{{item.capacidad-item.numeroPasajeros}}</td>
							<td>$ {{item.precioBoleto}}</td>
							<td>
								<span style="right:auto" class="new badge green" data-badge-caption="SI" 
									ng-show="item.disponibilidad"></span>
								<span style="right:auto" class="new badge red" data-badge-caption="NO" 
									ng-show="!item.disponibilidad"></span>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button class="waves-effect btn-flat grey lighten-3 right modal-action modal-close">
			<i class="material-icons left">close</i>
			Salir
		</button>
	</div>
</div>







