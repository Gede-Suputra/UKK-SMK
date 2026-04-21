<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreReturnRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'details' => 'required|array|min:1',
            'details.*.id_detail_pinjaman' => 'required|exists:detail_pinjaman,id',
            'details.*.jumlah_kembali' => 'required|integer|min:1',
            'details.*.tanggal_kembali' => 'required|date',
            'details.*.kondisi' => 'required|in:baik,rusak,hilang',
            'details.*.foto' => 'nullable|image|max:2048',
            'details.*.pesan' => 'nullable|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson() || $this->ajax()) {
            $response = response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            throw new HttpResponseException($response);
        }
        parent::failedValidation($validator);
    }
}
