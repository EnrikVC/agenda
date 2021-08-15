<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Phone;

class ContactController extends Controller
{
    public function listAll()
    {
    	$contacts = Contact::select('contacts.id', 'name', 'number')
    		->where('phones.isdefault', 1)
    		->orWhereNull('phones.id')
    		->leftJoin('phones', 'contacts.id', '=', 'phones.contact_id')
    		->orderBy('name', 'asc')
    		->get();
    	return view('contacts.landing')
    		->with('contactList', $contacts);
    }

    public function listAllJson()
    {
    	$contacts = Contact::select('contacts.id', 'name', 'number')
    		->where('phones.isdefault', 1)
    		->join('phones', 'contacts.id', '=', 'phones.contact_id')
    		->orderBy('name', 'asc')
    		->get();
    	return response()->json($contacts);
    }

    public function getByIdJson(Request $request)
    {
    	$contact = Contact::where('contacts.id', $request->contactId)
    		->first();

    	if ($contact) 
    	{
    		$phones = Phone::where('contact_id', $request->contactId)
    			->orderBy('isdefault', 'desc')
    			->get();
    		$contact->phones = $phones;
    	}

    	return response()->json($contact);
    }

    public function store(Request $request)
	{
		switch ($request->cboPhoneType) 
		{
			case 'M':
				$numberRule = 'bail|required|size:9|unique:phones,number|regex:/[0-9]{9}/';
				break;
			case 'C':
				$numberRule = 'bail|required|size:7|unique:phones,number|regex:/[0-9]{7}/';
				break;
			default: 
				$numberRule = 'bail|required|max:9|unique:phones,number|regex:/[0-9]/';
				break;
		}

		$rules = [
			'txtName' => 'bail|required|max:100|unique:contacts,name',
			'txtAddress' => 'max:200',
			'txtPhoneNumber' => $numberRule,
		];

		if ($request->txtEmail != '') 
		{
			$rules['txtEmail'] = 'bail|email|max:100|unique:contacts,email';
		}

		if ($request->validate($rules)) 
		{
			$output = ['success' => [], 'error' => []];
			$contact = new Contact;
			$contact->name = $request->txtName;
			$contact->address = $request->txtAddress;
			$contact->email = $request->txtEmail;

			if ($contact->save()) 
			{
				$output['success'][] = 'El nuevo contacto ha sido registrado.';
				$phone = new Phone;
				$phone->contact_id = $contact->id;
				$phone->type = $request->cboPhoneType;
				$phone->number = $request->txtPhoneNumber;
				$phone->isdefault = 1;

				if ($phone->save()) 
				{
					$output['success'][] = 'Un nuevo número ha sido registrado para el contacto.';
				}
				else
				{
					$output['error'][] = 'Error del sistema. No se pudo registrar el número telefónico.';
				}
			}
			else
			{
				$output['error'][] = 'Error del sistema. No se pudo registrar al nuevo contacto.';
			}

			if (count($output['success']) == 0) 
			{
				unset($output['success']);
			}

			if (count($output['error']) == 0) 
			{
				unset($output['error']);
			}

			return response()->json($output);
		}
	}

	public function delete(Request $request)
	{
		$output = ['success' => [], 'error' => []];
		$contact = Contact::find($request->id);

		if ($contact) 
    	{
    		Phone::where('contact_id', $request->id)
    			->delete();

    		if ($contact->delete()) 
    		{
    			$output['success'][] = 'El contacto ha sido eliminado.';
    		}
    		else
    		{
    			$output['error'][] = 'Error del sistema. No se pudo eliminar el contacto.';
    		}
    	}
    	else
    	{
    		$output['error'][] = 'No se pudo encontrar ni eliminar el contacto.';
    	}

    	if (count($output['success']) == 0) 
		{
			unset($output['success']);
		}

		if (count($output['error']) == 0) 
		{
			unset($output['error']);
		}

		return response()->json($output);
	}

	public function update($contactId)
	{
		$contact = Contact::find($contactId);

		if ($contact) 
    	{
    		$phones = Phone::where('contact_id', $contactId)
    			->orderBy('isdefault', 'desc')
    			->get();
    		$contact->phones = $phones;
    	}

    	return view('contacts.edit')
    		->with('contact', $contact);
	}

	public function doupdate(Request $request)
	{
		$rules = [
			'txtNameUpd' => 'bail|required|max:100|unique:contacts,name,'.$request->txtContactIdUpd,
			'txtAddressUpd' => 'max:200'
		];

		if ($request->txtEmailUpd != '') 
		{
			$rules['txtEmailUpd'] = 'bail|email|max:100|unique:contacts,email,'.$request->txtContactIdUpd;
		}

		if ($request->txtPhoneNumberUpdAdd != '')
		{
			$rules['txtPhoneNumberUpdAdd'] = 'bail|max:9|unique:phones,number|regex:/[0-9]/';
		}

		if ($request->cboPhoneTypeUpd) 
		{
			$ids = $request->txtPhoneId;
			$types = $request->cboPhoneTypeUpd;

			for ($i = 0; $i < count($types); $i++) 
			{ 
				switch ($types[$i]) 
				{
					case 'M':
						$numberRule = 'bail|required|size:9|unique:phones,number,'.$ids[$i].'|regex:/[0-9]{9}/';
						break;
					case 'C':
						$numberRule = 'bail|required|size:7|unique:phones,number,'.$ids[$i].'|regex:/[0-9]{7}/';
						break;
					default: 
						$numberRule = 'bail|required|max:9|unique:phones,number,'.$ids[$i].'|regex:/[0-9]/';
						break;
				}

				$rules['txtPhoneNumberUpd.'.$i] = $numberRule;
			}
		}

		if ($request->validate($rules)) 
		{
			$output = ['success' => [], 'error' => [], 'info' => []];
			$contact = Contact::find($request->txtContactIdUpd);

			if ($contact) 
			{
				$contact->name = $request->txtNameUpd;
				$contact->address = $request->txtAddressUpd;
				$contact->email = $request->txtEmailUpd;

				if ($contact->save()) 
				{
					$output['success'][] = 'El contacto ha sido editado.';
					$ids = $request->txtPhoneId;
					$types = $request->cboPhoneTypeUpd;
					$numbers = $request->txtPhoneNumberUpd;
					$editedNumbers = 0;

					for ($i = 0; $i < count($types); $i++) 
					{ 
						$phone = Phone::find($ids[$i]);
						$phone->type = $types[$i];
						$phone->number = $numbers[$i];

						if ($phone->save()) 
						{
							$editedNumbers++;
							//$output['success'] = [$editedNumbers.' números fueron editados.'];
						}
						/*else
						{
							$output['error'][] = 'Error del sistema. No se pudo registrar el número telefónico.';
						}*/
						//$output['info'][] = $numbers[$i];
					}
				}
				else
				{
					$output['error'][] = 'Error del sistema. No se pudo editar al contacto.';
				}
			}
			else
			{
				$output['error'][] = 'Error del sistema. No se pudo encontrar ni editar al contacto.';
			}

			if (count($output['success']) == 0) 
			{
				unset($output['success']);
			}

			if (count($output['error']) == 0) 
			{
				unset($output['error']);
			}

			return back()
				->withInput()
				->with('output', $output);
		}
	}

	public function addPhone(Request $request)
	{
		switch ($request->type) 
		{
			case 'M':
				$numberRule = 'bail|required|size:9|unique:phones,number|regex:/[0-9]{9}/';
				break;
			case 'C':
				$numberRule = 'bail|required|size:7|unique:phones,number|regex:/[0-9]{7}/';
				break;
			default: 
				$numberRule = 'bail|required|max:9|unique:phones,number|regex:/[0-9]/';
				break;
		}

		$rules = ['number' => $numberRule];

		if ($request->validate($rules)) 
		{
			$output = ['success' => [], 'error' => []];
			$phone = new Phone;
			$phone->contact_id = $request->contact_id;
			$phone->type = $request->type;
			$phone->number = $request->number;

			if ($phone->save()) 
			{
				$output['last_id'] = $phone->id;
				$output['success'][] = 'Un nuevo número ha sido registrado para el contacto.';
			}
			else
			{
				$output['error'][] = 'Error del sistema. No se pudo registrar el nuevo número.';
			}

			if (count($output['success']) == 0) 
			{
				unset($output['success']);
			}

			if (count($output['error']) == 0) 
			{
				unset($output['error']);
			}

			return response()->json($output);
		}
	}

	public function defaultPhone(Request $request)
	{
		$phone = Phone::find($request->id);
		$output = ['success' => [], 'error' => []];

		if ($phone) 
		{
			$contactId = $phone->contact_id;
			Phone::where('contact_id', $contactId)
				->update(['isdefault' => 0]);
			$phone->isdefault = 1;

			if ($phone->save()) 
			{
				$output['success'][] = 'El número '.$phone->number.' es ahora predeterminado.';
			}
			else
			{
				$output['error'][] = 'Error del sistema. No se pudo actualizar el número.';
			}
		}
		else
		{
			$output['error'][] = 'No se pudo encontrar ni actualizar el registro indicado.';
		}

		if (count($output['success']) == 0) 
		{
			unset($output['success']);
		}

		if (count($output['error']) == 0) 
		{
			unset($output['error']);
		}

		return response()->json($output);
	}

	public function deletePhone(Request $request)
	{
		$phone = Phone::find($request->id);
		$output = ['success' => [], 'error' => []];

		if ($phone) 
		{
			$contactId = $phone->contact_id;
			$deletedDefault = $phone->isdefault == 1;

			if ($phone->delete()) 
			{
				$output['success'][] = 'El número ha sido eliminado.';

				if ($deletedDefault) 
				{
					$nextPhone = Phone::where('contact_id', $contactId)->first();

					if ($nextPhone) 
					{
						$nextPhone->isdefault = 1;

						if ($nextPhone->save()) 
						{
							$output['default_id'] = $nextPhone->id;
						}
					}
				}
			}
			else
			{
				$output['error'][] = 'Error del sistema. No se pudo eliminar el número.';
			}
		}
		else
		{
			$output['error'][] = 'No se pudo encontrar ni eliminar el registro indicado.';
		}

		if (count($output['success']) == 0) 
		{
			unset($output['success']);
		}

		if (count($output['error']) == 0) 
		{
			unset($output['error']);
		}

		return response()->json($output);
	}
}
