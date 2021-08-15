@extends('layouts.main')

@section('title', ':: Edición de contacto')

@section('links')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/parsley-2.9.2/src/parsley.css') }}">
@endsection

@section('header', 'Edición de contacto')

@section('content')


@if (session()->has('output')) 
	@if (isset(session('output')['success']))
	<div class="alert alert-success mt-3">
		<ul class="mb-0 pl-3">
			@foreach (session('output')['success'] as $message)
			<li><small>{{ $message }}</small></li>
			@endforeach
		</ul>
	</div>
	@endif

	@if (isset(session('output')['error']))
	<div class="alert alert-danger mt-3">
		<ul class="mb-0 pl-3">
			@foreach (session('output')['error'] as $message)
			<li><small>{{ $message }}</small></li>
			@endforeach
		</ul>
	</div>
	@endif
@endif

@if ($errors->any())
<div class="alert alert-danger mt-3">
	<ul class="mb-0 pl-3">
		@foreach ($errors->all() as $error)
		<li><small>{{ $error }}</small></li>
		@endforeach
	</ul>
</div>
@endif

<div class="mt-3">
	<div class="card border-primary">
		<h5 class="card-header bg-primary text-white">Formulario de datos</h5>
		<div class="card-body">
			<form id="form_edit_contact" method="POST" action="../doupdate">
				@csrf
				<input type="hidden" id="txtContactIdUpd" name="txtContactIdUpd" value="{{ $contact->id }}">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="txtNameUpd">Nombre</label>
							<input type="text" class="form-control @error('txtNameUpd') is-invalid @enderror" id="txtNameUpd" name="txtNameUpd" maxlength="100" value="{{ old('txtNameUpd', $contact->name) }}" data-parsley-required="true">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="txtEmailUpd">Email</label>
							<input type="email" class="form-control @error('txtEmailUpd') is-invalid @enderror" id="txtEmailUpd" name="txtEmailUpd" maxlength="100" placeholder="Opcional" value="{{ old('txtEmailUpd', $contact->email) }}" data-parsley-type="email">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="txtAddressUpd">Direcci&oacute;n</label>
					<input type="text" class="form-control @error('txtAddressUpd') is-invalid @enderror" id="txtAddressUpd" name="txtAddressUpd" maxlength="200" placeholder="Opcional" value="{{ old('txtAddressUpd', $contact->address) }}">
				</div>
				<div class="row">
					<div class="col-md-5">
						Tipo de n&uacute;mero
					</div>
					<div class="col-md-7">
						N&uacute;mero
					</div>
				</div>
				<div id="phones_list">
					@if (!$contact->phones->isEmpty())
						@for ($i = 0; $i < count($contact->phones); $i++)
						<div class="row" id="phone_row_{{ $contact->phones[$i]->id }}">
							<div class="col-md-5">
								<div class="form-group">
									<input type="hidden" name="txtPhoneId[]" value="{{ $contact->phones[$i]->id }}">
									<select class="form-control" name="cboPhoneTypeUpd[]">
										<option value="" @if (old('cboPhoneTypeUpd.'.$i, $contact->phones[$i]->type) == '') selected @endif>Sin etiqueta</option>
										<option value="M" @if (old('cboPhoneTypeUpd.'.$i, $contact->phones[$i]->type) == 'M') selected @endif>M&oacute;vil</option>
										<option value="C" @if (old('cboPhoneTypeUpd.'.$i, $contact->phones[$i]->type) == 'C') selected @endif>Casa</option>
										<option value="T" @if (old('cboPhoneTypeUpd.'.$i, $contact->phones[$i]->type) == 'T') selected @endif>Trabajo</option>
									</select>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<input type="tel" class="form-control digits-only-list" name="txtPhoneNumberUpd[]" maxlength="9" value="{{ old('txtPhoneNumberUpd.'.$i, $contact->phones[$i]->number) }}" data-parsley-required="true" data-parsley-length="[7, 9]">
								</div>
							</div>
							<div class="col-md-2 text-right">
								<div class="form-group">
									<button type="button" class="btn btn-sm btn-primary mt-1 mr-1 icon-btn action-default @if ($contact->phones[$i]->isdefault == 1) d-none @endif" title="Volver predeterminado" onclick="CONTACT.defaultPhoneUpd({{ $contact->phones[$i]->id }})">
										<i class="fa fa-exclamation"></i>
									</button>
									<button type="button" class="btn btn-sm mt-1 mr-1 icon-btn p-0 info-default @if ($contact->phones[$i]->isdefault == 0) d-none @endif" title="Predeterminado">
										<i class="fa fa-check fa-2x text-success"></i>
									</button>
									<button type="button" class="btn btn-sm btn-danger mt-1 icon-btn" title="Eliminar" onclick="CONTACT.deletePhoneUpd({{ $contact->phones[$i]->id }})">
										<i class="fa fa-times"></i>
									</button>
								</div>
							</div>
						</div>
						@endfor
					@endif
				</div>
				<div class="row">
					<div class="col-md-5">
						<select class="form-control" id="cboPhoneTypeUpdAdd" name="cboPhoneTypeUpdAdd">
							<option value="" @if (old('cboPhoneTypeUpdAdd') == '') selected @endif>Sin etiqueta</option>
							<option value="M" @if (old('cboPhoneTypeUpdAdd') == 'M') selected @endif>M&oacute;vil</option>
							<option value="C" @if (old('cboPhoneTypeUpdAdd') == 'C') selected @endif>Casa</option>
							<option value="T" @if (old('cboPhoneTypeUpdAdd') == 'T') selected @endif>Trabajo</option>
						</select>
					</div>
					<div class="col-md-5">
						<input type="tel" class="form-control digits-only" id="txtPhoneNumberUpdAdd" name="txtPhoneNumberUpdAdd" value="{{ old('txtPhoneNumberUpdAdd') }}" maxlength="9" data-parsley-length="[7, 9]">
					</div>
					<div class="col-md-2 text-right">
						<button type="button" class="btn btn-sm btn-dark mt-1 icon-btn" title="Agregar otro" onclick="CONTACT.addPhoneUpd()">
							<i class="fa fa-pencil"></i>
						</button>
					</div>
				</div>
			</form>
		</div>
		<div class="card-footer text-right">
			<button type="button" class="btn btn-success" onclick="CONTACT.update()">Guardar datos</button>
			<a class="btn btn-secondary" href="../../">Volver a la lista</a>
		</div>
	</div>
</div>

@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/parsley-2.9.2/dist/parsley.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/parsley-2.9.2/dist/i18n/es.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/contact.js') }}"></script>
<script type="text/javascript">
	$(function(){
		$('.digits-only').inputFilter(function(value) {
			return /^\d*$/.test(value);
		});
	});
</script>
@endsection