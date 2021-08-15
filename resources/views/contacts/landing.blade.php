@extends('layouts.main')

@section('title', ':: Mi agenda telefónica')

@section('links')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatables-1.10.25/datatables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/parsley-2.9.2/src/parsley.css') }}">
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
@if ($contactList->isEmpty())
	<div class="alert alert-danger" role="alert">
		<i class="fa fa-exclamation-circle text-danger"></i> A&uacute;n no tienes contactos. Registra uno presionando &quot;Nuevo contacto&quot;.
	</div>
@endif
	<div class="d-none overflow-auto" id="div_list_all">
		<div class="card border-primary">
			<h5 class="card-header bg-primary text-white">Listado total</h5>
			<div class="card-body">
				<table class="table table-bordered" id="contacts_table" style="width:100%">
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Nro. predeterminado</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!--MODAL NUEVO REGISTRO-->
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
						<input type="text" class="form-control" id="txtName" name="txtName" maxlength="100">
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
								<input type="tel" class="form-control digits-only" id="txtPhoneNumber" name="txtPhoneNumber" maxlength="9">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="txtAddress">Direcci&oacute;n</label>
						<input type="text" class="form-control" id="txtAddress" name="txtAddress" maxlength="200" placeholder="Opcional">
					</div>
					<div class="form-group">
						<label for="txtEmail">Email</label>
						<input type="email" class="form-control" id="txtEmail" name="txtEmail" maxlength="100" placeholder="Opcional">
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
<!--FIN MODAL NUEVO REGISTRO-->

<!--MODAL VISTA REGISTRO-->
<div class="modal fade" id="contact_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="contact_modal_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="contact_modal_label">Datos de contacto</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form_upd_contact">
					<input type="hidden" id="txtContactIdUpd" name="txtContactIdUpd">
					<div class="form-group">
						<label for="txtNameUpd" class="label-bold">Nombre</label>
						<input type="text" readonly class="form-control-plaintext contact-view" id="txtNameView" maxlength="100">
						<input type="text" class="form-control contact-upd" id="txtNameUpd" name="txtNameUpd" maxlength="100">
					</div>
					<div id="div_has_phones">
						<div class="row contact-view">
							<div class="col-md-5 label-bold">
								Tipo de n&uacute;mero
							</div>
							<div class="col-md-7 label-bold">
								N&uacute;mero
							</div>
						</div>
						<div class="row contact-upd">
							<div class="col-md-5">
								Tipo de n&uacute;mero
							</div>
							<div class="col-md-7">
								N&uacute;mero
							</div>
						</div>
						<div id="phones_list" class="form-group"></div>
					</div>
					<div class="form-group contact-upd">
						<div class="row">
							<div class="col-md-5">
								<select class="form-control" id="cboPhoneTypeUpdAdd" name="cboPhoneTypeUpdAdd">
									<option value="">Sin etiqueta</option>
									<option value="M">M&oacute;vil</option>
									<option value="C">Casa</option>
									<option value="T">Trabajo</option>
								</select>
							</div>
							<div class="col-md-5">
								<input type="tel" class="form-control digits-only" id="txtPhoneNumberUpdAdd" name="txtPhoneNumberUpdAdd" maxlength="9" data-parsley-length="[7, 9]" data-parsley-errors-messages-disabled>
							</div>
							<div class="col-md-2 text-right">
								<button type="button" class="btn btn-sm btn-dark mt-1 icon-btn" title="Agregar otro" onclick="CONTACT.addPhoneUpd()">
									<i class="fa fa-pencil"></i>
								</button>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="txtAddressUpd" class="label-bold">Direcci&oacute;n</label>
						<input type="text" readonly class="form-control-plaintext contact-view" id="txtAddressView" maxlength="200">
						<input type="text" class="form-control contact-upd" id="txtAddressUpd" name="txtAddressUpd" maxlength="200" placeholder="Opcional">
					</div>
					<div class="form-group">
						<label for="txtEmailUpd" class="label-bold">Email</label>
						<input type="text" readonly class="form-control-plaintext contact-view" id="txtEmailView" maxlength="100">
						<input type="email" class="form-control contact-upd" id="txtEmailUpd" name="txtEmailUpd" maxlength="100" placeholder="Opcional">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<div class="contact-view">
					<!--<button type="button" class="btn btn-warning" onclick="CONTACT.updPrepare(true)">Editar</button>-->
					<a class="btn btn-warning" id="update_link">Editar</a>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
				<div class="contact-upd">
					<button type="button" class="btn btn-success" onclick="CONTACT.updSubmit()">Guardar</button>
					<!--<button type="button" class="btn btn-danger" onclick="CONTACT.updPrepare(false)">Cancelar</button>-->
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!--FIN MODAL VISTA REGISTRO-->
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/datatables-1.10.25/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/parsley-2.9.2/dist/parsley.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/parsley-2.9.2/dist/i18n/es.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/contact.js') }}"></script>
<script type="text/javascript">
	var contactList = [];
	@if ($contactList->isEmpty())
		Pace.restart();
	@else
		contactList = @json($contactList);
	@endif
	
	$(function(){
		$('.digits-only').inputFilter(function(value) {
			return /^\d*$/.test(value);
		});

		if (contactList.length > 0) 
		{
			CONTACT.listAll();
		}
	});
</script>
@endsection