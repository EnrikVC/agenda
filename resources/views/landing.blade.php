@extends('layouts.main')

@section('title', ':: Mi agenda telefónica')

@section('links')

@endsection

@section('header', 'Gestión de contactos')

@section('header_buttons')
<div class="float-md-right">
	<button type="button" class="btn btn-sm btn-primary" onclick="CONTACT.newForm()">
		<i class="fa fa-plus-square"></i> Nuevo contacto
	</button>
</div>
@endsection

@section('content')

@if ($errors->any())
<div class="alert alert-danger">
	<ul class="mb-0 pl-3">
		@foreach ($errors->all() as $error)
		<li><small>{{ $error }}</small></li>
		@endforeach
	</ul>
</div>
@endif

<div class="mt-3">
@if (isset($contacts)) 
@else
	<div class="alert alert-danger" role="alert">
		<i class="fa fa-exclamation-circle text-danger"></i> A&uacute;n no tienes contactos. Registra uno presionando &quot;Nuevo contacto&quot;.
	</div>
@endif
</div>

<div class="modal fade" id="new_contact_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="new_contact_modal_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="new_contact_modal_label">Registro de contacto</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form_new_contact">
					<div class="form-group">
						<label for="txtName">Nombre</label>
						<input type="text" class="form-control @error('name') is-invalid @enderror" id="txtName" name="txtName" maxlength="100">
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="cboPhoneType">Tipo de n&uacute;mero</label>
								<select class="form-control" id="cboPhoneType" name="cboPhoneType">
									<option value="" selected>Sin etiqueta</option>
									<option value="M">M&oacute;vil</option>
									<option value="C">Casa</option>
									<option value="T">Trabajo</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="txtPhoneNumber">N&uacute;mero</label>
								<input type="tel" class="form-control @error('number') is-invalid @enderror" id="txtPhoneNumber" name="txtPhoneNumber" maxlength="9">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="txtAddress">Direcci&oacute;n</label>
						<input type="text" class="form-control @error('address') is-invalid @enderror" id="txtAddress" name="txtAddress" maxlength="200" placeholder="Opcional">
					</div>
					<div class="form-group">
						<label for="txtEmail">Email</label>
						<input type="email" class="form-control @error('email') is-invalid @enderror" id="txtEmail" name="txtEmail" maxlength="100" placeholder="Opcional">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" onclick="CONTACT.newSubmit()">Guardar</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>

<!--<form id="formNewPR" method="POST" action="./store">
	@csrf
	<input type="hidden" id="prrq_sender_id" name="prrq_sender_id">
	<input type="hidden" id="prrq_refsender_id" name="prrq_refsender_id">
	<div class="card bg-primary mb-3">
		<div class="card-header text-white">
			Datos del Remitente
			<button type="button" class="btn btn-sm bg-transparent text-white pd-us float-right" data-toggle="collapse" data-target="#card_sender" onclick="UTIL.updateCollapser(this)">
				<i class="fa fa-minus"></i>
			</button>
			<div class="float-right mr-3 font-italic d-none" id="pr_sender_found">
				<small>Registro existente. Cualquier cambio en los datos no tendrá efecto.</small>
			</div>
		</div>
		<div class="card-body bg-white collapse show" id="card_sender">
			<div class="row">
				<div class="col-lg-2 col-md-4 form-group">
					<label for="prrq_dni">DNI</label>
					<input type="text" inputmode="numeric" class="form-control @error('prrq_dni') is-invalid @enderror" id="prrq_dni" name="prrq_dni" maxlength="8" placeholder="Obligatorio" value="{{ old('prrq_dni') }}" onblur="UTIL.PRRQ.getSenderByDNI(this.value, {name:'prrq_nombres', lastname:'prrq_apepat',mlastname:'prrq_apemat',email:'prrq_email',phone:'prrq_celular',address:'prrq_direccion',warnInto:'pr_sender_found',idInput:'prrq_sender_id'})">
				</div>
				<div class="col-lg-4 col-md-8 form-group">
					<label for="prrq_nombres">Nombres</label>
					<input type="text" class="form-control @error('prrq_nombres') is-invalid @enderror" id="prrq_nombres" name="prrq_nombres" maxlength="50" placeholder="Obligatorio" value="{{ old('prrq_nombres') }}">
				</div>
				<div class="col-lg-3 col-md-6 form-group">
					<label for="prrq_apepat">Apellido paterno</label>
					<input type="text" class="form-control @error('prrq_apepat') is-invalid @enderror" id="prrq_apepat" name="prrq_apepat" maxlength="50" placeholder="Obligatorio" value="{{ old('prrq_apepat') }}">
				</div>
				<div class="col-lg-3 col-md-6 form-group">
					<label for="prrq_nombres">Apellido materno</label>
					<input type="text" class="form-control @error('prrq_apemat') is-invalid @enderror" id="prrq_apemat" name="prrq_apemat" maxlength="50" placeholder="Opcional" value="{{ old('prrq_apemat') }}">
				</div>
				<div class="col-lg-4 col-md-7 form-group">
					<label for="prrq_email">Email</label>
					<input type="email" class="form-control @error('prrq_email') is-invalid @enderror" id="prrq_email" name="prrq_email" maxlength="50" placeholder="Obligatorio" value="{{ old('prrq_email') }}">
				</div>
				<div class="col-lg-2 col-md-5 form-group">
					<label for="prrq_celular">Celular</label>
					<input type="tel" class="form-control @error('prrq_celular') is-invalid @enderror" id="prrq_celular" name="prrq_celular" maxlength="9" placeholder="Obligatorio" value="{{ old('prrq_celular') }}">
				</div>
				<div class="col-lg-6 col-md-12 form-group">
					<label for="prrq_direccion">Direcci&oacute;n</label>
					<input type="text" class="form-control @error('prrq_direccion') is-invalid @enderror" id="prrq_direccion" name="prrq_direccion" maxlength="150" placeholder="Obligatorio" value="{{ old('prrq_direccion') }}">
				</div>

			</div>
		</div>
	</div>
	<div class="card bg-success mb-3 d-none" id="div_repsender">
		<div class="card-header text-white">
			Datos del Representado
			<button type="button" class="btn btn-sm bg-transparent text-white pd-us float-right" data-toggle="collapse" data-target="#card_repsender" onclick="UTIL.updateCollapser(this)">
				<i class="fa fa-minus"></i>
			</button>
			<div class="float-right mr-3 font-italic d-none" id="pr_refsender_found">
				<small>Registro existente. Cualquier cambio en los datos no tendrá efecto.</small>
			</div>
		</div>
		<div class="card-body bg-white collapse show" id="card_repsender">
			<div class="row div-repsender d-none" id="div_juridico">
				<div class="col-md-4 form-group">
					<label for="prrq_ruc">RUC</label>
					<input type="text" inputmode="numeric" class="form-control @error('prrq_ruc') is-invalid @enderror" id="prrq_ruc" name="prrq_ruc" maxlength="11" placeholder="Obligatorio" value="{{ old('prrq_ruc') }}">
				</div>
				<div class="col-md-8">
					<label for="prrq_razon">Raz&oacute;n social</label>
					<input type="text" class="form-control @error('prrq_razon') is-invalid @enderror" id="prrq_razon" name="prrq_razon" maxlength="60" placeholder="Obligatorio" value="{{ old('prrq_razon') }}">
				</div>
				<div class="col-lg-4 col-md-7 form-group">
					<label for="prrq_email_j">Email</label>
					<input type="email" class="form-control @error('prrq_email_j') is-invalid @enderror" id="prrq_email_j" name="prrq_email_j" maxlength="50" placeholder="Obligatorio" value="{{ old('prrq_email_j') }}">
				</div>
				<div class="col-lg-2 col-md-5 form-group">
					<label for="prrq_celular_j">Celular</label>
					<input type="tel" class="form-control @error('prrq_celular_j') is-invalid @enderror" id="prrq_celular_j" name="prrq_celular_j" maxlength="9" placeholder="Opcional" value="{{ old('prrq_celular_j') }}">
				</div>
				<div class="col-lg-6 col-md-12 form-group">
					<label for="prrq_direccion_j">Direcci&oacute;n</label>
					<input type="text" class="form-control @error('prrq_direccion_j') is-invalid @enderror" id="prrq_direccion_j" name="prrq_direccion_j" maxlength="150" placeholder="Obligatorio" value="{{ old('prrq_direccion_j') }}">
				</div>
			</div>
			<div class="d-none div-repsender" id="div_natural">
				<div class="row">
					<div class="col-lg-2 col-md-4 form-group">
						<label for="prrq_dni_n">DNI</label>
						<input type="text" inputmode="numeric" class="form-control @error('prrq_dni_n') is-invalid @enderror" id="prrq_dni_n" name="prrq_dni_n" maxlength="8" placeholder="Obligatorio" value="{{ old('prrq_dni_n') }}">
					</div>
					<div class="col-lg-4 col-md-8 form-group">
						<label for="prrq_nombres_n">Nombres</label>
						<input type="text" class="form-control @error('prrq_nombres_n') is-invalid @enderror" id="prrq_nombres_n" name="prrq_nombres_n" maxlength="50" placeholder="Obligatorio" value="{{ old('prrq_nombres_n') }}">
					</div>
					<div class="col-lg-3 col-md-6 form-group">
						<label for="prrq_apepat_n">Apellido paterno</label>
						<input type="text" class="form-control @error('prrq_apepat_n') is-invalid @enderror" id="prrq_apepat_n" name="prrq_apepat_n" maxlength="50" placeholder="Obligatorio" value="{{ old('prrq_apepat_n') }}">
					</div>
					<div class="col-lg-3 col-md-6 form-group">
						<label for="prrq_nombres_n">Apellido materno</label>
						<input type="text" class="form-control @error('prrq_apemat_n') is-invalid @enderror" id="prrq_apemat_n" name="prrq_apemat_n" maxlength="50" placeholder="Opcional" value="{{ old('prrq_apemat_n') }}">
					</div>
					<div class="col-lg-4 col-md-7 form-group">
						<label for="prrq_email_n">Email</label>
						<input type="email" class="form-control @error('prrq_email_n') is-invalid @enderror" id="prrq_email_n" name="prrq_email_n" maxlength="50" placeholder="Obligatorio" value="{{ old('prrq_email_n') }}">
					</div>
					<div class="col-lg-2 col-md-5 form-group">
						<label for="prrq_celular_n">Celular</label>
						<input type="tel" class="form-control @error('prrq_celular_n') is-invalid @enderror" id="prrq_celular_n" name="prrq_celular_n" maxlength="9" placeholder="Opcional" value="{{ old('prrq_celular_n') }}">
					</div>
					<div class="col-lg-6 col-md-12 form-group">
						<label for="prrq_direccion_n">Direcci&oacute;n</label>
						<input type="text" class="form-control @error('prrq_direccion_n') is-invalid @enderror" id="prrq_direccion_n" name="prrq_direccion_n" maxlength="150" placeholder="Obligatorio" value="{{ old('prrq_direccion_n') }}">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card bg-warning mb-3">
		<div class="card-header">Datos del Documento</div>
		<div class="card-body bg-white">
			<div class="row">
				<div class="col-lg-5 col-md-8 form-group">
					<label for="prrq_tipodocumento">Tipo de documento</label>
					<select class="form-control select-2 @error('prrq_tipodocumento') is-invalid @enderror" id="prrq_tipodocumento" name="prrq_tipodocumento">
						<option value="">Seleccione</option>
						
					</select>
				</div>
				<div class="col-lg-3 col-md-4 form-group">
					<label for="prrq_folios">N&deg; de folios</label>
					<input type="text" inputmode="numeric" class="form-control @error('prrq_folios') is-invalid @enderror" id="prrq_folios" name="prrq_folios" maxlength="2" placeholder="Obligatorio" value="{{ old('prrq_folios') }}">
				</div>
				<div class="col-md-12 form-group">
					<label for="prrq_asunto">Asunto</label>
					<textarea class="form-control @error('prrq_asunto') is-invalid @enderror" id="prrq_asunto" name="prrq_asunto" rows="2" maxlength="200" placeholder="Obligatorio">{{ old('prrq_asunto') }}</textarea>
				</div>


			</div>
		</div>
	</div>
</form>-->
@endsection

@section('scripts')

<script type="text/javascript">
	Pace.restart();
	$(function(){
		/*var uploaded;
		var Toast = UTIL.getToast();

		$('.select-2').select2({
			theme: 'bootstrap4'
		});



		function filesToList(ulId, paths, sizes, PRRQfn, hiddenName)
		{
			var filename, uid;

			for (var i = 0; i < paths.length; i++) 
			{
				uid = Math.floor(Math.random() * Date.now()) + i;
				filename = paths[i].split('/');
				$('#' + ulId).append('<li class="list-group-item">' + 
					'<i class="fa fa-trash text-danger mr-3" onclick="PRRQ.' + PRRQfn + '(' + uid + ')" role="button"></i>' + UTIL.bytesToSize(sizes[i]) + ' - <i>' + filename[filename.length - 2] + '</i>' + 
					'<input type="hidden" id="' + uid + '" name="' + hiddenName + '" value="' + filename[filename.length - 2] + '/' + filename[filename.length - 1] + '">' + 
					'</li>' + 
					'<input type="hidden" name="sz' + hiddenName + '" value="' + sizes[i] + '">' + 
				'</li>');
			}

			$('#' + ulId).removeClass('d-none');
		}

		@if (old('prrq_representacion', null) != null)
			PRRQ.displayRepSenderCard('{{ old("prrq_representacion") }}');
		@endif;

		var _paths_, _sizes_;
		@if (old('prrq_archivo_ruta', null) != null)
			_paths_ = @json(old('prrq_archivo_ruta'));
			_sizes_ = @json(old('szprrq_archivo_ruta'));
			_paths_ = Array.isArray(_paths_) ? _paths_ : [_paths_];
			_sizes_ = Array.isArray(_sizes_) ? _sizes_ : [_sizes_];
			filesToList('ul-archivo', _paths_, _sizes_, 'deleteTmpDocument', 'prrq_archivo_ruta');
			$('#prrq_archivo').fileinput('clear');
			$('#prrq_archivo').fileinput('disable');
		@endif;

		@if (old('prrq_anexo_ruta', null) != null)
			_paths_ = @json(old('prrq_archivo_ruta'));
			_sizes_ = @json(old('szprrq_archivo_ruta'));
			_paths_ = Array.isArray(_paths_) ? _paths_ : [_paths_];
			_sizes_ = Array.isArray(_sizes_) ? _sizes_ : [_sizes_];
			filesToList('ul-anexos', @json(old('prrq_anexo_ruta')), @json(old('szprrq_anexo_ruta')), 'deleteTmpAttachment', 'prrq_anexo_ruta[]');
		@endif;

		@if (session()->has('output')) 
			PRRQ.showStoreOutput(@json(session('output')));
		@endif*/
	});
</script>
<script type="text/javascript">
	var CONTACT = {
		newForm: function(){
			$('#new_contact_modal').modal('show');
		},
		newSubmit: function(){
			var nsRq;
			Pace.restart();
			nsRq = $.ajax({
				url: './contact/store',
				method: 'POST',
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				data: $('#form_new_contact').serialize(),
				dataType: 'json'
			});
			nsRq.done(function (data) { 
				CONTACT.newContactOutput(data);
				$('#new_contact_modal').modal('hide');
			});
			nsRq.fail(function (jqXHR, textStatus) {
				if (jqXHR.status == 422) 
				{
					var messages = CONTACT.formatJsonResponse(jqXHR.responseJSON);
					CONTACT.newContactOutput(messages);
				}
				else
				{
					console.log(textStatus + '||' + jqXHR.responseText);
				}
			});
			nsRq.always(function () {});
		},
		newContactOutput: function(output){
			var icon, successList = '', errorList = '', successDisplay = '', errorDisplay = '';

			if (output.success && output.error) 
			{
				icon = 'warning';
				output.success.forEach(element => successList += '<li><small>' + element + '</small></li>');
				output.error.forEach(element => errorList += '<li><small>' + element + '</small></li>');
			}
			else
			{
				successDisplay = 'd-none';
				errorDisplay = 'd-none';

				if (output.success) 
				{
					icon = 'success';
					successDisplay = '';
					output.success.forEach(element => successList += '<li><small>' + element + '</small></li>');
				}
				else if (output.error) 
				{
					icon = 'error';
					errorDisplay = '';
					output.error.forEach(element => errorList += '<li><small>' + element + '</small></li>');
				}
			}

			Swal.fire({
				icon: icon,
				title: 'Resultado de registro',
				html: '<div class="alert alert-success ' + successDisplay + '">' + 
					'<ul class="mb-0 pl-3 text-left">' + successList + '</ul>' + 
				'</div>' + 
				'<div class="alert alert-danger ' + errorDisplay + '">' + 
					'<ul class="mb-0 pl-3 text-left">' + errorList + '</ul>' + 
				'</div>',
				confirmButtonColor: '#007bff',
				confirmButtonText: 'Ok'
			});
		},
		formatJsonResponse: function(json){
			var messages = {};

			if (json.errors) 
			{
				var errors = [];
				Object.entries(json.errors).forEach(element => errors.push(element[1][0]));
				messages.error = errors;
			}

			return messages;
		}
	};
/*
	var UTIL = {
		bytesToSize: function(a, b = 2){
			if (0 === a)
				return "0 Bytes";
			const c = 0 > b ? 0 : b, d = Math.floor(Math.log(a)/Math.log(1024));

			return parseFloat((a/Math.pow(1024, d)).toFixed(c)) + " " + ["Bytes","KB","MB","GB","TB","PB","EB","ZB","YB"][d];
		},
		getToast: function(){
			return Swal.mixin({
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 3000
			});
		},
		updateCollapser: function(btn){
			var icon = $(btn).children('i')[0];
			if ($(icon).hasClass('fa-minus')) 
			{
				$(icon).removeClass('fa-minus').addClass('fa-plus');
			}
			else
			{
				$(icon).removeClass('fa-plus').addClass('fa-minus');
			}
		},
		PRRQ: {
			vars: {
				dfRq: null
			},
			clearSenderFilled: function(fillable){
				if (fillable.warnInto) 
				{
					$('#' + fillable.warnInto).addClass('d-none');
				}

				if (fillable.idInput) 
				{
					$('#' + fillable.idInput).val('');
				}

				if (fillable.name) 
				{
					$('#' + fillable.name).val('');
				}

				if (fillable.lastname) 
				{
					$('#' + fillable.lastname).val('');
				}

				if (fillable.mlastname) 
				{
					$('#' + fillable.mlastname).val('');
				}

				if (fillable.email) 
				{
					$('#' + fillable.email).val('');
				}

				if (fillable.phone) 
				{
					$('#' + fillable.phone).val('');
				}

				if (fillable.address) 
				{
					$('#' + fillable.address).val('');
				}
			},
			getSenderByDNI: function(dni, fillable){
				this.clearSenderFilled(fillable);

				if (dni.length == 8) 
				{
					Pace.restart();
					var _Toast = UTIL.getToast();
					this.vars.dfRq = $.ajax({
						url: '../util/getprsenderdata',
						method: 'POST',
						data: {
							'_token': '{{ csrf_token() }}',
							'dni': dni
						},
						dataType: 'json'
					});
					this.vars.dfRq.done(function (data) {
						if (data.length > 0) 
						{
							data = data[0];
							
							if (fillable.idInput) 
							{
								$('#' + fillable.idInput).val(data.id);
							}

							if (fillable.name) 
							{
								$('#' + fillable.name).val(data.name);
							}

							if (fillable.lastname) 
							{
								$('#' + fillable.lastname).val(data.lastname);
							}

							if (fillable.mlastname) 
							{
								$('#' + fillable.mlastname).val(data.mlastname);
							}

							if (fillable.email) 
							{
								$('#' + fillable.email).val(data.email);
							}

							if (fillable.phone) 
							{
								$('#' + fillable.phone).val(data.phone);
							}

							if (fillable.address) 
							{
								$('#' + fillable.address).val(data.address);
							}

							if (fillable.warnInto) 
							{
								$('#' + fillable.warnInto).removeClass('d-none');
							}
						}
					});
					this.vars.dfRq.fail(function (jqXHR, textStatus) {
						_Toast.fire({
							icon: 'error',
							title: 'Un error en el proceso ha impedido verificar el DNI.'
						});
						console.log(textStatus + '<br>' + jqXHR.responseText);
					});
					this.vars.dfRq.always(function () {});
				}
			}
		}
	}

	var PRRQ = {
		Toast: UTIL.getToast(),
		vars: {
			dfRq: null
		},
		deleteTmpAttachment: function(pathInputId){
			Swal.fire({
				title: '¿Seguro de eliminar?',
				text: '',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#28a745',
				cancelButtonColor: '#dc3545',
				confirmButtonText: 'Eliminar',
				cancelButtonText: 'Cancelar'
			}).then((result) => {
				if (result.isConfirmed) 
				{
					var input = $('#' + pathInputId);
					var inputRow = input.parent();

					nsRq = $.ajax({
						url: './deletetmp',
						method: 'POST',
						data: {
							'_token': '{{ csrf_token() }}',
							'path': input.val(),
							'type': 'attachments'
						},
						dataType: 'json'
					});
					nsRq.done(function (data) {
						if (data.success) 
						{
							inputRow.remove();
							$('#prrq_anexo').fileinput('enable');

							if ($('input[name="prrq_anexo_ruta[]"]').length == 0) 
							{
								$('#ul-anexos').empty().addClass('d-none');
							}

							PRRQ.Toast.fire({
								icon: 'success',
								title: 'Anexo eliminado correctamente.'
							});
						}
						else
						{
							PRRQ.Toast.fire({
								icon: 'error',
								title: 'No se ha podido eliminar el anexo.'
							});
						}
					});
					nsRq.fail(function (jqXHR, textStatus) {
						PRRQ.Toast.fire({
							icon: 'error',
							title: 'Un error en el proceso ha impedido eliminar el archivo.'
						});
						console.log(textStatus + '<br>' + jqXHR.responseText);
					});
					nsRq.always(function () {});
				}
			});
		},
		deleteTmpDocument: function(pathInputId){
			Swal.fire({
				title: '¿Seguro de eliminar?',
				text: 'Podrá volver a cargar un nuevo documento.',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#28a745',
				cancelButtonColor: '#dc3545',
				confirmButtonText: 'Eliminar',
				cancelButtonText: 'Cancelar'
			}).then((result) => {
				if (result.isConfirmed) 
				{
					nsRq = $.ajax({
						url: './deletetmp',
						method: 'POST',
						data: {
							'_token': '{{ csrf_token() }}',
							'path': $('#' + pathInputId).val(),
							'type': 'documents'
						},
						dataType: 'json'
					});
					nsRq.done(function (data) {
						if (data.success) 
						{
							$('#ul-archivo').empty().addClass('d-none');
							$('#prrq_archivo').fileinput('enable');

							PRRQ.Toast.fire({
								icon: 'success',
								title: 'Documento eliminado correctamente.'
							});
						}
						else
						{
							PRRQ.Toast.fire({
								icon: 'error',
								title: 'No se ha podido eliminar el documento.'
							});
						}
					});
					nsRq.fail(function (jqXHR, textStatus) {
						PRRQ.Toast.fire({
							icon: 'error',
							title: 'Un error en el proceso ha impedido eliminar el archivo.'
						});
						console.log(textStatus + '<br>' + jqXHR.responseText);
					});
					nsRq.always(function () {});
				}
			});
		},
		displayRepSenderCard: function(value){
			$('.div-repsender').addClass('d-none');

			switch (value) 
			{
				case 'NBP':
				case '1':
				$('#div_repsender').addClass('d-none');
				break;
				case 'OPN':
				case '2':
				$('#div_repsender, #div_natural').removeClass('d-none');
				break;
				case 'PSJ':
				case '3':
				$('#div_repsender, #div_juridico').removeClass('d-none');
				break;
			}
		},
		showStoreOutput: function(output){
			var icon, successList = '', errorList = '', successDisplay = '', errorDisplay = '';

			if (output.success && output.error) 
			{
				icon = 'warning';
				output.success.forEach(element => successList += '<li><small>' + element + '</small></li>');
				output.error.forEach(element => errorList += '<li><small>' + element + '</small></li>');
			}
			else
			{
				successDisplay = 'd-none';
				errorDisplay = 'd-none';

				if (output.success) 
				{
					icon = 'success';
					successDisplay = '';
					output.success.forEach(element => successList += '<li><small>' + element + '</small></li>');
				}
				else if (output.error) 
				{
					icon = 'error';
					errorDisplay = '';
					output.error.forEach(element => errorList += '<li><small>' + element + '</small></li>');
				}
			}

			Swal.fire({
				icon: icon,
				title: 'Resultado de registro',
				html: '<div class="alert alert-success ' + successDisplay + '">' + 
					'<ul class="mb-0 pl-3 text-left">' + successList + '</ul>' + 
				'</div>' + 
				'<div class="alert alert-danger ' + errorDisplay + '">' + 
					'<ul class="mb-0 pl-3 text-left">' + errorList + '</ul>' + 
				'</div>',
				footer: '<a class="text-primary" onclick="PRRQ.printQR(\'' + (output.gencode ? output.gencode : '') + '\')" role="button">Imprimir QR</a>'
			});
		},
		printQR: function(gencode){
			if (gencode != '') 
			{
				window.open('./printqr/' + gencode, '_blank');
			}
		},
		submitNew: function(){
			var proceed = true;

			if ($('input[name="prrq_archivo_ruta"]').length == 0) 
			{
				proceed = false;
				Swal.fire({
					icon: 'error',
					title: 'Datos incompletos',
					text: 'No puede enviar la solicitud sin haber adjuntado y cargado el documento principal.'
				});
			}

			if ($('#prrq_anexo')[0].files.length > 0 && $('input[name="prrq_anexo_ruta[]"]').length == 0) 
			{
				proceed = false;
				Swal.fire({
					icon: 'warning',
					title: 'Archivos sin cargar',
					text: 'Se han seleccionado anexos pero no han sido cargados. Puede cerrar este mensaje para cargarlos.',
					showCancelButton: true,
					confirmButtonColor: '#28a745',
					cancelButtonColor: '#dc3545',
					confirmButtonText: 'Enviar de todos modos',
					cancelButtonText: 'Cerrar'
				}).then((result) => {
					if (result.isConfirmed) 
					{
						$('#formNewPR').submit();
					}
				});
			}

			if (proceed) 
			{
				$('#formNewPR').submit();
			}
		}
	};*/
</script>
@endsection