<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;

class StorePinjamanRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'id_peminjam' => 'required|exists:users,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'pesan' => 'nullable|string',
            'disetujui_oleh' => 'nullable|exists:users,id',
            'diselesaikan_oleh' => 'nullable|exists:users,id',
            'details' => 'required|array|min:1',
            // support both naming conventions: details.*.alat_id or details.*.id_alat
            'details.*.alat_id' => 'required|exists:alats,id',
            'details.*.jumlah' => 'required|integer|min:1',
        ];
    }

    protected function prepareForValidation()
    {
        $details = $this->input('details', []);
        \Log::info('StorePinjamanRequest raw details before normalization', ['details_raw' => $details]);

        // If details was sent as JSON string, try to decode it
        if (is_string($details)) {
            $json = json_decode($details, true);
            if (is_array($json)) {
                $details = $json;
            }
        }

        // If a single object was sent (not an indexed array), wrap it
        if (is_array($details) && Arr::isAssoc($details)) {
            // If it contains alat keys like alat_id or id_alat, assume single item
            if (isset($details['alat_id']) || isset($details['id_alat']) || isset($details['jumlah'])) {
                $details = [$details];
            }
        }

        if (is_array($details) && count($details) > 0) {
            foreach ($details as $i => $d) {
                if (!is_array($d)) continue;
                // normalize id_alat -> alat_id for validation consistency
                if (isset($d['id_alat']) && !isset($d['alat_id'])) {
                    $details[$i]['alat_id'] = $d['id_alat'];
                }
                // accept alternative key names
                if (isset($d['id_alat']) && !isset($details[$i]['alat_id'])) {
                    $details[$i]['alat_id'] = $d['id_alat'];
                }
                if (isset($d['alatId']) && !isset($details[$i]['alat_id'])) {
                    $details[$i]['alat_id'] = $d['alatId'];
                }
                // also normalize jumlah string to int
                if (isset($d['jumlah'])) {
                    $details[$i]['jumlah'] = is_numeric($d['jumlah']) ? (int) $d['jumlah'] : $d['jumlah'];
                }
                if (isset($d['qty']) && !isset($details[$i]['jumlah'])) {
                    $details[$i]['jumlah'] = is_numeric($d['qty']) ? (int) $d['qty'] : $d['qty'];
                }
            }
            $this->merge(['details' => $details]);
        }
        
        \Log::info('StorePinjamanRequest normalized details', ['details_normalized' => $details]);
    }

    protected function failedValidation(Validator $validator)
    {
        \Log::error('StorePinjamanRequest validation failed', [
            'errors' => $validator->errors()->toArray(),
            'input' => $this->except(['pesan']),
        ]);
        
        if ($this->expectsJson() || $this->ajax()) {
            $response = response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            throw new HttpResponseException($response);
        }
        parent::failedValidation($validator);
    }
}
