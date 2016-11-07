<?php 
	@session_start();
	include '../class/session.class.php';
	$session = new Session();
	if ( $session->getProfile() != 1 ) {
		echo "<h4 class='red-text text-darken-2'>No Tiene Acceso a este modulo</h4>";
		exit();
	}
?>
<div class="col m12">
	<div class="row">
		<blockquote class="row s12 m12">
			<div class="col m6 s12">
		 		<h5>
		 			<i class="material-icons">people</i> Agente de Viaje
		 		</h5>
			</div>
		</blockquote>

		<!-- AGREGAR VUELO -->
		<div class="col s12 center-align">
			<a class="waves-effect blue lighten-1 btn" ng-click="openReservacion()">
				<i class="material-icons left">add</i> Reservacion
				<i class="material-icons right">people</i>
			</a>
			<a class="waves-effect cyan darken-1 btn" ng-click="openConsultas()">
				<i class="material-icons left">pageview</i> Comisiones
			</a>
		</div>
	</div>
	<div class="hr"></div>
	<div class="row">
		<h5><b>Reservaciones por Fecha</b></h5>
		<div class="col s12">
			<div class="col m4 s12">
				<b class="col m4 s12">De Fecha</b>
				<div class="col m8 s12">
					<input type="text" class="datepicker" id="vuelosDeFecha" name="vuelosDeFecha" value="<?= date('Y-m-01');?>">
				</div>
			</div>
			<div class="col m4 s12">
				<b class="col m4 s12">Para Fecha</b>
				<div class="col m8 s12">
					<input type="text" class="datepicker" id="vuelosParaFecha" name="vuelosParaFecha" value="<?= date('Y-m-d');?>">
				</div>
			</div>
			<div class="col m4 s12">
				<button type="button" class="waves-effect btn" ng-click="getVuelos()">
					<i class="material-icons left">airplanemode_active</i> Consultar
				</button>
			</div>
		</div>
	</div>
</div>

<div class="col m12">
	<div class="row itemVuelo" ng-repeat="itemVuelo in lstVuelos">
		<!-- vuelo -->
		<div class="col s12" style="margin-top: 10px">
			<div class="col m2 s12">
				<b># Vuelo: </b>{{itemVuelo.idVuelo}}
			</div>
			<div class="col m3 s12">
				<b>Aeronave: </b>{{itemVuelo.aeronave}} ({{itemVuelo.tipoAeronave}})
			</div>
			<div class="col m3 s12">
				<b>Estado Vuelo: </b>{{itemVuelo.estadoVuelo}}
			</div>
			<div class="col m4 s12">
				<b>Tiempo Vuelo Aproximado: </b>{{itemVuelo.tiempoViaje}}
			</div>
		</div>
		<div class="col s12" style="margin-top: 10px">
			<div class="col m8 s12">
				<b>Origen: </b> <u>{{itemVuelo.origen}}</u>, {{itemVuelo.ciudadOrigen}}, {{itemVuelo.paisOrigen}}, {{itemVuelo.continenteOrigen}}
			</div>
			<div class="col m4 s12">
				<b>Salida: </b> {{itemVuelo.fechaSalida}} - {{itemVuelo.horaSalida}}
			</div>
		</div>
		<div class="col s12" style="margin-top: 10px">
			<div class="col m8 s12">
				<b>Destino: </b> <u>{{itemVuelo.destino}}</u>, {{itemVuelo.ciudadDestino}}, {{itemVuelo.paisDestino}}, {{itemVuelo.continenteDestino}}
			</div>
			<div class="col m4 s12">
				<b>Aterrizaje: </b> {{itemVuelo.fechaAterrizaje}} - {{itemVuelo.horaAterrizaje}}
			</div>
		</div>
		<div class="col s12" style="margin-top: 10px">
			<button class="waves-effect waves-light btn" ng-click="itemVuelo.show=!itemVuelo.show">
				<i class="material-icons left">people_outline</i>
				Ver Pasajeros Referidos (<b>{{itemVuelo.lstPasajero.length}}</b>)
			</button>
		</div>

		<!-- PASAJEROS -->
		<div class="col s12" style="margin-top: 20px" ng-show="itemVuelo.show">
			<table class="responsive-table">
				<thead>
					<tr>
						<th>Foto</th>
						<th># Asiento</th>
						<th># Pasaporte</th>
						<th>Clase</th>
						<th>Pasajero</th>
						<th>Edad</th>
						<th>Forma Pago</th>
						<th>Estado Reserv.</th>
						<th>Precio Boleto</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="item in itemVuelo.lstPasajero">
						<td>
							<img ng-src="fotos/{{item.urlFoto}}" height="30">
						</td>
						<td>{{item.numeroAsiento}}</td>
						<td>{{item.numeroPasaporte}}</td>
						<td>{{item.clase}}</td>
						<td>{{item.nombreCompleto}}</td>
						<td>{{item.edad}}</td>
						<td>{{item.tipoPago}}</td>
						<td>{{item.estadoReservacion}}</td>
						<td>$ {{item.precioBoleto}}</td>
						<td style="min-width: 30px">
							<button type="button" class="btn min red lighten-1 tooltip" data-title="Cancelar" 
								ng-click="openCancelarReservacion( item )" ng-show="item.idEstadoReservacion==1">
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

<!-- Modal CONSULTAS -->
<div id="mdlConsultas" class="modal bottom-sheet">
	<div class="modal-content">
		<form class="col s12">
			<h5>Monto total de comisiones</h5>
			<div class="hr"></div>

			<div class="row">
				<div class="col s12">
					<div class="col m4 s12">
						<b class="col m5 s12">De Fecha</b>
						<div class="col m7 s12">
							<input type="text" class="datepicker" id="deFecha">
						</div>
					</div>
					<div class="col m4 s12">
						<b class="col m5 s12">Para Fecha</b>
						<div class="col m7 s12">
							<input type="text" class="datepicker" id="paraFecha">
						</div>
					</div>
					<div class="col m4 s12">
						<button type="button" class="waves-effect cyan darken-1 btn" ng-click="consultarComisiones()">
							<i class="material-icons left">pageview</i> Consultar
						</button>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<h5>Total Comisiones: <b>$ {{comisionTotal | number:2}}</b></h5>
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

<!-- Modal CANCELAR RESERVACION -->
<div id="mdlCancelar" class="modal bottom-sheet">
	<div class="modal-content">
		<h4>
			<i class="material-icons">people</i>
			Esta seguro de cancelar la Reservación?
		</h4>
		<div class="hr"></div>
		<div class="row">
			<div class="col s12">
				<h5>Nombre: <b>{{reservacion.nombreCompleto}}</b></h5>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button class="waves-effect btn red lighten-1 left" ng-click="cancelarReservacion()">
			<i class="material-icons left">delete</i>
			Cancelar Reservación
		</button>
		<button class="waves-effect btn-flat grey lighten-3 right modal-action modal-close">
			<i class="material-icons left">close</i>
			Salir
		</button>
	</div>
</div>





