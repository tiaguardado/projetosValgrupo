<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEncomendaRequest extends FormRequest
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
            'tamanhoPizza' => 'required|max:55',
            'basePizza' => 'required|max:55',
            'ingredientesExtra' => 'max:255',
            'estadoPedido' => 'in:Pendente,Concluido,Cancelado',

        ];
    }
}
