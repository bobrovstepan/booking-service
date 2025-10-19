<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingStoreRequest extends FormRequest
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
            'data.option' => 'required',
            'data.day' => 'required',
            'data.start_time' => 'required',
            'data.user.name' => 'required',
            'data.user.phone_number' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'data.option.required' => 'Выберите продолжительность!',
            'data.day.required' => 'Выберите дату!',
            'data.start_time.required' => 'Выберите время начала!',
            'data.user.name.required' => 'Укажите имя!',
            'data.user.phone_number' => 'Укажите номер телефона!',
        ];
    }
}
