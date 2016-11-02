<div class="col m12">
	<div class="row">
		<blockquote class="row s12 m12">
			<div class="col m6 s12">
		 		<h5>
		 			<i class="material-icons">people</i> Reservación
		 		</h5>
			</div>
		</blockquote>

		<!-- AGREGAR VUELO -->
		<div class="col s12 center-align">
			<a class="waves-effect waves-light btn" ng-click="openReservacion()">
				<i class="material-icons left">add</i> Reservacion
				<i class="material-icons right">people</i>
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
						<td style="min-width: 150px">
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


<!-- Modal AGREGAR PERSONA -->
<div id="mdlPersona" class="modal bottom-sheet">
	<div class="modal-content">
		<h4>
			<i class="material-icons">account_circle</i>
			Agregar Persona
		</h4>
		<div class="hr"></div>

		<form class="col s12">
			<div class="row">
				<div class="col s12 m6">
					<div class="input-field col s12">
						<input id="icon_prefix" type="text" class="validate" ng-model="newPersona.numeroPasaporte">
						<label for="icon_prefix">Número Pasaporte</label>
					</div>
					<div class="input-field col s12">
						<input id="icon_prefix" type="text" class="validate" ng-model="newPersona.identificacion">
						<label for="icon_prefix">Identificación</label>
					</div>
				</div>
				<!-- FOTO DE PASAPORTE -->
				<div class="col s12 m6">
					<div class="input-field col s12">
						<input id="multiple" type="file" multiple>
					</div>
					<div class="col s12">
						<img class="responsive-img" ng-src="fotos/{{newPersona.urlFoto}}" style="height: 120px">
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col s12">
					<div class="input-field col m6 s12">
						<input id="icon_prefix" type="text" class="validate" ng-model="newPersona.nombres">
						<label for="icon_prefix">Nombres</label>
					</div>
					<div class="input-field col m6 s12">
						<input id="icon_prefix" type="text" class="validate" ng-model="newPersona.apellidos">
						<label for="icon_prefix">Apellidos</label>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col s12">
					<b class="col m1 s12">Genero</b>
					<div class="col m3 s12">
						<select ng-model="newPersona.idGenero" class="browser-default">
							<option value="m">Masculino</option>
							<option value="f">Femenino</option>
						</select>
					</div>
					<div class="col m6 s12">
						<b class="col m4 s12">Fecha Nacimiento</b>
						<div class="col m5 s12">
							<input type="text" class="datepicker" id="fechaNacimiento">
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col s12">
					<div class="input-field col m6 s12">
						<input id="icon_prefix" type="text" class="validate" ng-model="newPersona.correo">
						<label for="icon_prefix">Correo</label>
					</div>
					<div class="input-field col m6 s12">
						<input id="icon_prefix" type="text" class="validate" ng-model="newPersona.telefono">
						<label for="icon_prefix">Teléfono</label>
					</div>
				</div>
			</div>

			<!-- RESIDENCIA -->
			<div class="row">
				<div class="col s12">
					<b class="col m2 s12">Continente</b>
					<div class="col m2 s12">
						<select class="browser-default" ng-model="idContinente">
							<option value="{{item.idContinente}}" ng-repeat="item in lstContinente">{{item.continente}}</option>
						</select>
					</div>
					<b class="col m1 s12">Pais</b>
					<div class="col m3 s12">
						<select class="browser-default" ng-model="codigoPais">
							<option value="{{item.codigoPais}}" ng-repeat="item in lstPais">{{item.pais}}</option>
						</select>
					</div>
					<b class="col m1 s12">Ciudad</b>
					<div class="col m3 s12">
						<select class="browser-default" ng-model="newPersona.idCiudad">
							<option value="{{item.idCiudad}}" ng-repeat="item in lstCiudad">{{item.ciudad}}</option>
						</select>
					</div>
				</div>
			</div>
		</form>			
	</div>
	<div class="modal-footer">
		<button class="waves-effect green btn left" ng-click="guardarPersona()">
			<i class="material-icons left">done</i>
			Guardar
		</button>
		<button class="waves-effect btn-flat grey lighten-3 right modal-action modal-close">
			<i class="material-icons left">close</i>
			Salir
		</button>
	</div>
</div>

<!-- Modal RESERVACION -->
<div id="mdlReservacion" class="modal bottom-sheet">
	<div class="modal-content">
		<h4>
			Agregar Reservación 
			<i class="material-icons">people</i>
		</h4>
		<div class="hr"></div>
		<form class="col s12">
			<div class="row" ng-hide="persona.idPersona>0">
				<div class="input-field col m6 s12">
					<input id="icon_prefix" type="text" class="validate" ng-model="numeroPasaporte" autocomplete="off">
					<label for="icon_prefix">Número Pasaporte</label>
				</div>
				<div class="input-field col m6 s12">
					<button class="waves-effect blue lighten-1 btn left" ng-click="consultarPersona( true )">
						<i class="material-icons left">search</i>
						Buscar
					</button>
				</div>
			</div>
			<div class="row" ng-show="persona.idPersona>0">
				<div class="col m2 s12">
					<img class="responsive-img" ng-src="fotos/{{persona.urlFoto}}" style="max-height: 300px">
				</div>
				<div class="col m10 s12">
					<div class="row">
						<div class="col m6 s12">
							<span>ID Persona: </span>
							<b>{{persona.idPersona}}</b>
						</div>
						<div class="col m6 s12">
							<span># Pasaporte: </span>
							<b>{{persona.numeroPasaporte}}</b>
						</div>
					</div>
					<div class="row">
						<div class="col m5 s12">
							<span>Nombre: </span>
							<b>{{persona.nombres +" "+ persona.apellidos}}</b>
						</div>
						<div class="col m3 s12">
							<span>Edad: </span>
							<b>{{persona.edad}}</b>
						</div>
						<div class="col m4 s12">
							<span>Genero: </span>
							<b>{{persona.genero}}</b>
						</div>
					</div>
					<div class="row">
						<div class="col m4 s12">
							<span>Identificación: </span>
							<b>{{persona.identificacion}}</b>
						</div>
						<div class="col m4 s12">
							<span>Teléfono: </span>
							<b>{{persona.telefono}}</b>
						</div>
						<div class="col m4 s12">
							<span>Correo: </span>
							<b>{{persona.correo}}</b>
						</div>
					</div>
					<div class="row">
						<div class="col m4 s12">
							<span>Nacionalidad: </span>
							<b>{{persona.nacionalidad}}</b>
						</div>
						<div class="col m4 s12">
							<span>Ciudad: </span>
							<b>{{persona.ciudad}}</b>
						</div>
						<div class="col m4 s12">
							<span>Pais: </span>
							<b>{{persona.pais}}</b>
						</div>
					</div>
					<div class="row" ng-show="destino.idVuelo">
						<div class="col s12">
							<div class="chip">
								<b>{{destino.aeropuertoDestino}}, {{destino.ciudadDestino}}, {{destino.paisDestino}}</b>
								<i class="material-icons right" style="cursor:pointer;margin-top:4px" ng-click="destino={};buscarDestino='';idClase=''">close</i>
							</div>
						</div>
					</div>

					<!-- BUSQUEDA DE DESTINO -->
					<div class="row" ng-hide="destino.idVuelo">
						<div class="col s12">
							<div class="input-field col m12 s12">
								<input id="icon_prefix" type="text" class="validate" ng-model="buscarDestino"
									autocomplete="off">
								<label for="icon_prefix">Destino</label>
								<ul class="autocomplete-content dropdown-content">
									<li ng-repeat="item in lstDestino" ng-click="selDestino( item )">
										<span>
											<span class="myItem" ng-class="{'danger':!item.disponibilidad}">
												{{item.aeropuertoDestino}}
											</span> 
											<span class="myItem" ng-class="{'danger':!item.disponibilidad}">
												{{item.ciudadDestino+", "+item.paisDestino}}
											</span>
											<span class="myItem" ng-class="{'danger':!item.disponibilidad}">
												{{item.fechaSalida+" - "+item.horaSalida}}
											</span>
											<span class="myItem" ng-class="{'danger':!item.disponibilidad}">
												<b>{{item.horasFaltantes}} horas para la salida</b>
											</span>
										</span>
									</li>
									<li ng-show="lstDestino.length==0 && buscarDestino.length>2">
										<span>Sin Resultados</span>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- BUSQUEDA DE DESTINO -->
				</div>
			</div>

			<!-- DESTINO ELEGIDO -->
			<div class="row" ng-show="destino.idVuelo>0">
				<ul class="collection">
					<li class="collection-item">
						<div class="row">
							<div class="col l4 m7 s12">
								<span>Aeronave: </span>
								<b>{{destino.aeronave}} ({{destino.tipoAeronave}})</b>
							</div>
							<div class="col l2 m5 s12">
								<span>Disponibilidad: </span>
								<b ng-show="destino.disponibilidad">SI</b>
								<b ng-show="!destino.disponibilidad">NO</b>
							</div>
							<div class="col l3 m6 s12">
								<span>Despega en: </span>
								<b>{{destino.horasFaltantes}} horas</b>
							</div>
							<div class="col l3 m6 s12">
								<span>Salida: </span>
								<b>{{destino.fechaSalida}} - {{destino.horaSalida}}</b>
							</div>
						</div>
						<div class="row">
							<div class="col m6 s12">
								<span>Origen: </span>
								<b>{{destino.aeropuertoOrigen}}, {{destino.ciudadOrigen}}, {{destino.paisOrigen}}</b>
							</div>
							<div class="col m6 s12">
								<span>Destino: </span>
								<b>{{destino.aeropuertoDestino}}, {{destino.ciudadDestino}}, {{destino.paisDestino}}</b>
							</div>
						</div>
					</li>
				</ul>
			</div>

			<!-- FORMULARIO -->
			<div class="row" ng-show="destino.idVuelo>0">
				<b class="col m2 s12">Tipo de Pago</b>
				<div class="col m3 s12">
					<select class="browser-default" ng-model="idTipoPago">
						<option value="{{item.idTipoPago}}" ng-repeat="item in lstTipoPago">{{item.tipoPago}}</option>
					</select>
				</div>
				<b class="col m2 s12">Clase</b>
				<div class="col m4 s12">
					<select class="browser-default" ng-model="idClase">
						<option value="{{item.idClase}}" ng-repeat="item in destino.lstClase">
							$ {{item.precioBoleto}} » {{item.clase}} » Disp.: {{item.capacidad-item.numeroPasajeros}}
						</option>
					</select>
				</div>
			</div>

			<!-- RESPONSABLE -->
			<div class="row" ng-show="destino.idVuelo > 0 && persona.menorEdad && !responsable.idPersona">
				<div class="col m10 s12">
					<div class="input-field col s12">
						<input id="icon_prefix" type="text" class="validate" ng-model="pasaporteResponsable"
							autocomplete="off">
						<label for="icon_prefix">Responsable</label>
					</div>
				</div>
				<div class="col m2 s12">
					<button class="waves-effect blue lighten-1 btn left" ng-click="consultarPersona( false )">
						<i class="material-icons left">search</i>
					</button>
				</div>
				<div class="col s12">
					<div class="card-panel">
						<span class="red-text text-darken-2">Si el menor no tiene un responsable, se hará un recargo de: <b>$100.00</b></span>
					</div>
				</div>
			</div>
			<div class="row" ng-show="responsable.idPersona">
				<div class="col m2 s12"><b>Responsable</b></div>
				<div class="col m10 s12">
					<div class="chip">
						<img ng-src="fotos/{{responsable.urlFoto}}">
						<b>{{responsable.nombres}} {{responsable.apellidos}} ({{responsable.numeroPasaporte}})</b> - {{responsable.ciudad}}, {{responsable.pais}}
						<i class="material-icons right" style="cursor:pointer;margin-top:4px" ng-click="responsable={}">close</i>
					</div>
				</div>
			</div>

			<!-- TOTAL -->
			<div class="row" ng-show="destino.idVuelo > 0">
				<div class="col s12">
					<h4 class="right-align">TOTAL: <b>$ {{totalReservacion | number:2}}</b></h4>
				</div>
			</div>
		</form>			
	</div>
	<div class="modal-footer">
		<button class="waves-effect green btn left" ng-click="guardarReservacion()">
			<i class="material-icons left">done</i>
			Guardar
		</button>
		<button class="waves-effect btn-flat grey lighten-3 right modal-action modal-close">
			<i class="material-icons left">close</i>
			Salir
		</button>
	</div>
</div>

<!-- Modal CONFIRMACION DE RESERVACION -->
<div id="mdlReservacionConf" class="modal bottom-sheet">
	<div class="modal-content">
		<h4>
			<i class="material-icons">people</i>
			Reservación
		</h4>
		<div class="hr"></div>

		<form class="col s12">
			<div class="row">
				<div class="col s12">
					<img class="responsive-img" ng-src="fotos/{{reservacion.urlFoto}}" style="height: 120px">
				</div>
				<!-- DATOS DE RESERVACION -->
				<div class="col s12">
					<div class="col m4 s12">
						<b># Reservación: </b>{{reservacion.idReservacion}}
					</div>
					<div class="col m4 s12">
						<b># Asiento: </b>{{reservacion.numeroAsiento}}
					</div>
					<div class="col m4 s12">
						<b># Pasaporte: </b>{{reservacion.numeroPasaporte}}
					</div>
				</div>
				<div class="col s12">
					<div class="col m6 s12">
						<b>Nombre: </b>{{reservacion.nombreCompleto}}
					</div>
					<div class="col m6 s12">
						<b>Estado: </b>{{reservacion.estadoReservacion}}
					</div>
				</div>
				<div class="col s12">
					<div class="col m6 s12">
						<b>Encargado: </b>{{reservacion.nombreEncargado}}
					</div>
				</div>
			</div>
		</form>			
	</div>
	<div class="modal-footer">
		<button class="waves-effect btn-flat grey lighten-3 right modal-action modal-close">
			<i class="material-icons left">close</i>
			Salir
		</button>
	</div>
</div>





