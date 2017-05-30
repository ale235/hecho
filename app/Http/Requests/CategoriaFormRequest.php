<?php

namespace ideas\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoriaFormRequest extends FormRequest
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
        // No son los nombres de los atributos en las tablas de la base de datos, si no que son los nombres que va a tener el formulario html
        return [
            'nombre' => 'required|max:45',
            'descripcion' => 'max:256'
        ];
    }
}
