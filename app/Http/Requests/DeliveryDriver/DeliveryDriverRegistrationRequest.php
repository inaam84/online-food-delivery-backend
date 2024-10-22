<?php

namespace App\Http\Requests\DeliveryDriver;

use App\Enums\DeliveryDriverRegistrationStatus;
use App\Enums\DeliveryDriverStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class DeliveryDriverRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:50',
            'surname' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:delivery_drivers',
            'password' => 'required|string|min:8|confirmed',

            'landline_phone' => 'nullable|string|max:50',
            'mobile_phone' => 'required|string|max:50',
            'address_line_1' => 'required|string|max:70',
            'town' => 'nullable|string|max:70',
            'city' => 'nullable|string|max:70',
            'county' => 'nullable|string|max:70',
            'postcode' => 'required|string|max:15',

            'registration_status' => ['nullable', new Enum(DeliveryDriverRegistrationStatus::class)],
            'status' => ['nullable', new Enum(DeliveryDriverStatus::class)],
        ];
    }
}
