<?php

namespace App\Http\Requests\DeliveryDriver;

use App\Enums\DeliveryVehicleStatus;
use App\Enums\DeliveryVehicleType;
use App\Models\DeliveryDriver;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class DeliveryVehicleStoreUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'delivery_driver_id' => 'required|in:'.DeliveryDriver::pluck('id')->implode(','),
            'type' => ['required', new Enum(DeliveryVehicleType::class)],
            'registration_number' => 'nullable|string|max:15',
            'year' => 'nullable|numeric',
            'status' => ['nullable', new Enum(DeliveryVehicleStatus::class)],
        ];
    }
}
