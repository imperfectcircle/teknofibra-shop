<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'title' => 'required|max:2000',
            'images.*' => 'nullable|image',
            'deleted_images.*' => 'nullable|numeric',
            'image_positions.*' => 'nullable|numeric',
            'categories.*' => 'nullable|numeric|exists:categories,id',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'published' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Il campo titolo è un campo obbligatorio',
            'title.max' => 'Il campo titolo deve avere massimo 2000 caratteri',
            'images.image' => 'Inserisci un immagine con formato valido (jpg, jpeg, png, bmp, svg o webp)',
            'price.required' => 'Il campo prezzo è un campo obbligatorio',
            'price.numeric' => 'Il campo prezzo deve essere un numero',
            'quantity.numeric' => 'Il campo quantità deve essere un numero',
            'description.string' => 'Il campo descrizione deve essere una stringa',
        ];
    }
}
