<?php

namespace App\Http\Requests\Offer;

use App\Models\Offer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOfferRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('offers')->ignore($this->route('offer')),
            ],
            'image' => [
                'sometimes',
                'image',
                'file',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
                'dimensions:min_width=100,min_height=100'],
            'description' => ['nullable', 'string', 'max:255'],
            'state' => ['required', 'string', 'in:'.implode(',', array_keys(Offer::$states))],
        ];
    }
}
