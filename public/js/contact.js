const phoneTestField = '#txtPhoneNumberUpdAdd';
var phoneTest = $(phoneTestField).parsley();
const updForm = '#form_edit_contact';
var updTest = $(updForm).parsley();
var CONTACT = {
	phoneTypes: {
		M: 'Móvil',
		C: 'Casa',
		T: 'Trabajo'
	},
	addPhoneUpd: function(){
		//$(phoneTestField).removeClass('is-invalid');

		if ($(phoneTestField).val() != '') 
		{
			phoneTest.validate();

			if (phoneTest.isValid()) 
			{
				var apRq;
				Pace.restart();
				apRq = $.ajax({
					url: '../addPhone',
					method: 'POST',
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					data: {
						contact_id: $('#txtContactIdUpd').val(),
						type: $('#cboPhoneTypeUpdAdd').val(),
						number: $('#txtPhoneNumberUpdAdd').val()
					},
					dataType: 'json'
				});
				apRq.done(function (data) {
					CONTACT.opContactOutput(data);

					if (data.last_id) 
					{
						$('#phones_list').append(CONTACT.getPhoneListItem({
							id: data.last_id,
							type: $('#cboPhoneTypeUpdAdd').val(),
							number: $(phoneTestField).val(),
							isdefault: 0
						}, false));
						$('#cboPhoneTypeUpdAdd').val('');
						$(phoneTestField).val('')
					}
				});
				apRq.fail(function (jqXHR, textStatus) {
					if (jqXHR.status == 422) 
					{
						var messages = CONTACT.formatJsonResponse(jqXHR.responseJSON);
						CONTACT.opContactOutput(messages);
					}
					else
					{
						console.log(textStatus + ' || ' + jqXHR.responseText);
					}
				});
				apRq.always(function () {});
			}
			/*else
			{
				$(phoneTestField).addClass('is-invalid');
				Swal.fire({
					icon: 'error',
					title: 'Validación de número',
					html: 'El valor ingresado debe contener entre 7 y 9 dígitos',
					confirmButtonColor: '#007bff',
					confirmButtonText: 'Ok'
				});
			}*/
		}
		else
		{
			$(phoneTestField).addClass('is-invalid');
		}
	},
	defaultPhoneUpd: function(phoneId){
		var dpRq;
		Pace.restart();
		dpRq = $.ajax({
			url: '../defaultPhone',
			method: 'POST',
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			data: {	id: phoneId },
			dataType: 'json'
		});
		dpRq.done(function (data) { 
			var title = data.success ? data.success[0] : (data.error ? data.error[0] : '...');
			Swal.fire({
				title: title,
				confirmButtonColor: '#007bff'
			});
			
			if (data.success) 
			{
				$('.info-default').addClass('d-none');
				$('.action-default').removeClass('d-none');
				$('#phone_row_' + phoneId).find('.info-default').removeClass('d-none');
				$('#phone_row_' + phoneId).find('.action-default').addClass('d-none');
			}
		});
		dpRq.fail(function (jqXHR, textStatus) {
			console.log(textStatus + ' || ' + jqXHR.responseText);
		});
		dpRq.always(function () {});
	},
	delete: function(contactId){
		Swal.fire({
			title: 'Seguro de eliminar el contacto?',
			text: 'Se eliminarán también todos los números vinculados.',
			showCancelButton: true,
			confirmButtonText: 'Eliminar!',
			cancelButtonText: 'Cancelar',
			confirmButtonColor: '#007bff',
			cancelButtonColor: '#dc3545'
		}).then((result) => {
			if (result.isConfirmed) 
			{
				var dcRq, refresh = false;
				Pace.restart();
				dcRq = $.ajax({
					url: './contact/delete',
					method: 'POST',
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					data: {	id: contactId },
					dataType: 'json'
				});
				dcRq.done(function (data) { 
					var title = data.success ? data.success[0] : (data.error ? data.error[0] : '...');
					Swal.fire({
						title: title,
						confirmButtonColor: '#007bff'
					});

					if (data.success) 
					{
						refresh = true;
					}
				});
				dcRq.fail(function (jqXHR, textStatus) {
					console.log(textStatus + ' || ' + jqXHR.responseText);
				});
				dcRq.always(function () {
					if (refresh) 
					{
						CONTACT.listAll();
					}
				});
			}
		});
	},
	deletePhoneUpd: function(phoneId){
		Swal.fire({
			title: 'Seguro de eliminar el número?',
			showCancelButton: true,
			confirmButtonText: 'Eliminar!',
			cancelButtonText: 'Cancelar',
			confirmButtonColor: '#007bff',
			cancelButtonColor: '#dc3545'
		}).then((result) => {
			if (result.isConfirmed) 
			{
				var dpRq;
				Pace.restart();
				dpRq = $.ajax({
					url: '../deletePhone',
					method: 'POST',
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					data: {	id: phoneId },
					dataType: 'json'
				});
				dpRq.done(function (data) { 
					var title = data.success ? data.success[0] : (data.error ? data.error[0] : '...');
					Swal.fire({
						title: title,
						confirmButtonColor: '#007bff'
					});

					if (data.success) 
					{
						$('#phone_row_' + phoneId).remove();

						if (data.default_id) 
						{
							$('#phone_row_' + data.default_id).find('.info-default').removeClass('d-none');
							$('#phone_row_' + data.default_id).find('.action-default').addClass('d-none');
						}
					}
				});
				dpRq.fail(function (jqXHR, textStatus) {
					console.log(textStatus + ' || ' + jqXHR.responseText);
				});
				dpRq.always(function () {});
			}
		});
	},
	newForm: function(){
		$('#txtName, #cboPhoneType, #txtPhoneNumber, #txtAddress, #txtEmail').val('');
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
			CONTACT.opContactOutput(data);
			CONTACT.listAll();
			$('#new_contact_modal').modal('hide');
		});
		nsRq.fail(function (jqXHR, textStatus) {
			if (jqXHR.status == 422) 
			{
				var messages = CONTACT.formatJsonResponse(jqXHR.responseJSON);
				CONTACT.opContactOutput(messages);
			}
			else
			{
				console.log(textStatus + ' || ' + jqXHR.responseText);
			}
		});
		nsRq.always(function () {});
	},
	opContactOutput: function(output, title = 'Resultado del registro'){
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
			title: title,
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
	},
	listAll: function(){
		if (contactList.length > 0) 
		{
			this.DTConfig.data = contactList;
			$('#contacts_table').DataTable(this.DTConfig);
			contactList = [];
			$('#div_list_all').removeClass('d-none');
		}
		else
		{
			var laRq;
			Pace.restart();
			laRq = $.ajax({
				url: './contact/listAllJson',
				method: 'POST',
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				data: '',
				dataType: 'json'
			});
			laRq.done(function (data) { 
				$('#contacts_table').DataTable().clear().rows.add(data).draw();
			});
			laRq.fail(function (jqXHR, textStatus) {
				console.log(textStatus + ' || ' + jqXHR.responseText);
			});
			laRq.always(function () {
				$('#div_list_all').removeClass('d-none');
			});
		}
	},
	getByIdJson: function(id){
		var gbiRq;
		Pace.restart();
		gbiRq = $.ajax({
			url: './contact/getByIdJson',
			method: 'POST',
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			data: { contactId: id },
			dataType: 'json'
		});
		gbiRq.done(function (data) { 
			data.address = data.address == null ? '---' : data.address;
			data.email = data.email == null ? '---' : data.email;
			$('#div_has_phones').removeClass('d-none');
			$('#update_link').attr('href', './contact/update/' + id);
			$('#cboPhoneTypeUpdAddm, #txtPhoneNumberUpdAdd').val('');
			$('#txtContactIdUpd').val(id);
			$('#txtNameUpd, #txtNameView').val(data.name);
			$('#txtAddressUpd, #txtAddressView').val(data.address);
			$('#txtEmailUpd, #txtEmailView').val(data.email);
			$('#phones_list').empty();

			if (data.phones.length == 0) 
			{
				$('#div_has_phones').addClass('d-none');
			}
			else
			{
				data.phones.forEach(element => $('#phones_list').append(CONTACT.getPhoneListItem(element)));
			}
			
			CONTACT.updPrepare(false);
			$('#contact_modal').modal('show');
			$('.digits-only-list').inputFilter(function(value) {
				return /^\d*$/.test(value);
			});
		});
		gbiRq.fail(function (jqXHR, textStatus) {
			console.log(textStatus + ' || ' + jqXHR.responseText);
		});
		gbiRq.always(function () {});
	},
	getPhoneListItem: function(element, genView = true){
		return (genView ? '<div class="row contact-view">' + 
			'<div class="col-md-5">' + 
				'<div class="">' + 
					'<input type="text" readonly class="form-control-plaintext" value="' + (element.type == '' ? 'Sin etiqueta' : CONTACT.phoneTypes[element.type]) + '">' + 
				'</div>' + 
			'</div>' + 
			'<div class="col-md-5">' + 
				'<div class="">' + 
					'<input type="text" readonly class="form-control-plaintext" value="' + element.number + '">' + 
				'</div>' + 
			'</div>' + 
			(element.isdefault == 1 ? 
				'<div class="col-md-2">' + 
					'<div class="">' + 
						'<i class="fa fa-check fa-2x text-success" title="Predeterminado"></i>' + 
					'</div>' + 
				'</div>' : '') +
		'</div>' : 
		'<div class="row contact-upd">' + 
			'<div class="col-md-5">' + 
				'<div class="form-group">' + 
					'<input type="hidden" name="txtPhoneId[]" value="' + element.id + '">' + 
					'<select class="form-control" name="cboPhoneTypeUpd[]">' + 
						'<option value="" ' + (element.type == '' ? 'selected' : '') + '>Sin etiqueta</option>' + 
						'<option value="M" ' + (element.type == 'M' ? 'selected' : '') + '>M&oacute;vil</option>' + 
						'<option value="C" ' + (element.type == 'C' ? 'selected' : '') + '>Casa</option>' + 
						'<option value="T" ' + (element.type == 'T' ? 'selected' : '') + '>Trabajo</option>' + 
					'</select>' + 
				'</div>' + 
			'</div>' + 
			'<div class="col-md-5">' + 
				'<div class="form-group">' + 
					'<input type="tel" class="form-control digits-only-list" name="txtPhoneNumberUpd[]" maxlength="9" value="' + element.number + '" data-parsley-required="true" data-parsley-length="[7, 9]" data-parsley-errors-messages-disabled>' + 
				'</div>' + 
			'</div>' + 
			'<div class="col-md-2 text-right">' + 
				'<div class="form-group">' + 
					(element.isdefault == 0 ? 
						'<button type="button" class="btn btn-sm btn-primary mt-1 mr-2 icon-btn" title="Volver predeterminado">' + 
							'<i class="fa fa-exclamation"></i>' + 
						'</button>' : 
						'<button type="button" class="btn btn-sm mt-1 mr-1 icon-btn p-0" title="Predeterminado">' + 
							'<i class="fa fa-check fa-2x text-success"></i>' + 
						'</button>') + 
					'<button type="button" class="btn btn-sm btn-danger mt-1 icon-btn" title="Eliminar">' + 
						'<i class="fa fa-times"></i>' + 
					'</button>' + 
				'</div>' + 
			'</div>' +
		'</div>');
	},
	update: function(){
		updTest.validate();

		if (updTest.isValid()) 
		{
			$(updForm).submit();
		}
	},
	updPrepare: function(editable){
		if (editable) 
		{
			$('.contact-view').addClass('d-none');
			$('.contact-upd').removeClass('d-none');
		}
		else
		{
			$('.contact-view').removeClass('d-none');
			$('.contact-upd').addClass('d-none');
		}
	},
	updSubmit: function(){
		$('.parsley-error').removeClass('is-invalid');
		updTest.reset();
		updTest.validate();

		if (updTest.isValid()) 
		{
			var usRq;
			Pace.restart();
			usRq = $.ajax({
				url: './contact/update',
				method: 'POST',
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				data: $(updForm).serialize(),
				dataType: 'json'
			});
			usRq.done(function (data) { 
				CONTACT.opContactOutput(data);
				CONTACT.listAll();
				$('#contact_modal').modal('hide');
			});
			usRq.fail(function (jqXHR, textStatus) {
				if (jqXHR.status == 422) 
				{
					var messages = CONTACT.formatJsonResponse(jqXHR.responseJSON);
					CONTACT.opContactOutput(messages);
				}
				else
				{
					console.log(textStatus + ' || ' + jqXHR.responseText);
				}
			});
			usRq.always(function () {});
		}
		else
		{
			$('.parsley-error').addClass('is-invalid');
		}
	},
	DTConfig: {
		columns: [
			{ data: 'name' },
			{ data: 'number' },
			{ data: null }
		],
		columnDefs: [ 
			{
				targets: 1,
				render: function ( data, type, row, meta ) {
					return row.number == null ? '---' : row.number;
				}
			},
			{
				targets: 2,
				width: '100px',
				render: function ( data, type, row, meta ) {
					return '<div class="text-center">' + 
						'<button type="button" class="btn btn-sm border-secondary" title="Ver ficha" onclick="CONTACT.getByIdJson(' + row.id + ')">' + 
							'<i class="fa fa-eye"></i>' + 
						'</button>' + 
						'<button type="button" class="btn btn-sm btn-danger ml-1 icon-btn" title="Eliminar" onclick="CONTACT.delete(' + row.id + ')">' + 
							'<i class="fa fa-times"></i>' + 
						'</button>' + 
							/*'<button class="btn btn-sm btn-warning dropdown-toggle ml-1" type="button" id="dd' + row.id + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 32px;"></button>' + 
							'<div class="dropdown-menu" aria-labelledby="dd' + row.id + '">' + 
								'<a class="dropdown-item" href="#">Editar</a>' + 
								'<a class="dropdown-item" href="#">Eliminar</a>' + 
							'</div>' + */
					'</div>';
				}
			}
		],
		lengthChange: false,
		ordering: false,
		language: {
			"info": "Mostrando página _PAGE_ de _PAGES_",
			"search": "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
		}
	}
};