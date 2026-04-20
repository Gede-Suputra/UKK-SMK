@php $isEdit = isset($pinjaman) && $pinjaman->exists; @endphp

<form id="pinjaman-form" method="POST" action="{{ $isEdit ? route('pinjaman.update', $pinjaman) : route('pinjaman.store') }}">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold">Peminjam</label>
                <select name="id_peminjam" required class="w-full px-3.5 py-2.5 rounded-xl border">
                    <option value="">— Pilih Peminjam —</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" @if(old('id_peminjam', $pinjaman->id_peminjam ?? '') == $u->id) selected @endif>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold">Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', $pinjaman->tanggal_pinjam ?? date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 rounded-xl border">
            </div>
            <div>
                <label class="block text-xs font-semibold">Tanggal Kembali Rencana</label>
                <input type="date" name="tanggal_kembali_rencana" value="{{ old('tanggal_kembali_rencana', $pinjaman->tanggal_kembali_rencana ?? '') }}" required class="w-full px-3.5 py-2.5 rounded-xl border">
            </div>
            <div>
                <label class="block text-xs font-semibold">Status</label>
                <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border">
                    @foreach(['pending','approved','active','returned','cancelled'] as $s)
                        <option value="{{ $s }}" @if(old('status', $pinjaman->status ?? 'pending') == $s) selected @endif>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold">Pesan</label>
            <textarea name="pesan" class="w-full px-3.5 py-2.5 rounded-xl border">{{ old('pesan', $pinjaman->pesan ?? '') }}</textarea>
        </div>

        <div>
            <h4 class="text-sm font-semibold mb-2">Detail Peminjaman</h4>
            <div id="detail-rows">
                @php $rows = old('details', $pinjaman->details->map(function($d){ return ['id_alat'=>$d->id_alat,'jumlah'=>$d->jumlah,'status'=>$d->status]; })->toArray() ?? []); @endphp
                @if(is_array($rows) && count($rows))
                    @foreach($rows as $i => $r)
                        <div class="detail-row flex items-center gap-2 mb-2">
                            <select name="details[{{ $i }}][id_alat]" class="px-3 py-2 rounded border">
                                <option value="">— Pilih Alat —</option>
                                @foreach($alats as $alat)
                                    <option value="{{ $alat->id }}" @if(($r['id_alat'] ?? '') == $alat->id) selected @endif>{{ $alat->nama_alat }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="details[{{ $i }}][jumlah]" min="1" value="{{ $r['jumlah'] ?? 1 }}" class="px-3 py-2 rounded border w-28" />
                            <select name="details[{{ $i }}][status]" class="px-3 py-2 rounded border">
                                @foreach(['pending','active','returned','cancelled'] as $ss)
                                    <option value="{{ $ss }}" @if(($r['status'] ?? '') == $ss) selected @endif>{{ ucfirst($ss) }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="remove-row px-3 py-2 rounded bg-red-50 border">Hapus</button>
                        </div>
                    @endforeach
                @endif
            </div>
            <div>
                <button type="button" id="add-row" class="px-3 py-2 rounded bg-indigo-600 text-white">Tambah Item</button>
            </div>
        </div>

        <div class="flex items-center justify-between pt-5 border-t">
            <button type="button" data-modal-close class="px-4 py-2 rounded border">Batal</button>
            <button type="submit" class="px-6 py-2 rounded bg-indigo-600 text-white">{{ $isEdit ? 'Simpan Perubahan' : 'Buat Peminjaman' }}</button>
        </div>
    </div>
</form>

<script>
    (function(){
        const container = document.getElementById('detail-rows');
        const addBtn = document.getElementById('add-row');
        function makeRow(index){
            const div = document.createElement('div'); div.className = 'detail-row flex items-center gap-2 mb-2';
            const select = document.createElement('select'); select.name = `details[${index}][id_alat]`; select.className='px-3 py-2 rounded border';
            select.innerHTML = '<option value="">— Pilih Alat —</option>' + `{!! collect($alats)->map(fn($a) => "<option value=\"{$a->id}\">{$a->nama_alat}</option>")->join('') !!}`;
            const input = document.createElement('input'); input.type='number'; input.name=`details[${index}][jumlah]`; input.min=1; input.value=1; input.className='px-3 py-2 rounded border w-28';
            const st = document.createElement('select'); st.name=`details[${index}][status]`; st.className='px-3 py-2 rounded border'; st.innerHTML = '<option value="pending">Pending</option><option value="active">Active</option><option value="returned">Returned</option><option value="cancelled">Cancelled</option>';
            const btn = document.createElement('button'); btn.type='button'; btn.className='remove-row px-3 py-2 rounded bg-red-50 border'; btn.textContent='Hapus';
            btn.addEventListener('click', ()=>div.remove());
            div.appendChild(select); div.appendChild(input); div.appendChild(st); div.appendChild(btn);
            return div;
        }
        let idx = container.querySelectorAll('.detail-row').length || 0;
        addBtn?.addEventListener('click', function(){ container.appendChild(makeRow(idx)); idx++; });
        container.addEventListener('click', function(e){ if(e.target.classList.contains('remove-row')) e.target.closest('.detail-row')?.remove(); });
    })();
</script>
