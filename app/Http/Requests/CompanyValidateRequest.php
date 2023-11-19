<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyValidateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                'company_name' => ['required'],
                'company_type' => ['required'],
                'reg_number' => [''],
                'vat_number' => [''],
                'email_address_1' => [''],
                'email_address_2' => [''],
                'email_address_3' => [''],
                'telephone_number_1' => [''],
                'telephone_number_2' => [''],
                'website' => [''],
                'business_address' => [''],
                'business_address_postal_code' => [''],
                'postal_address' => [''],
                'postal_address_code' => [''],
        ];
    }
}
