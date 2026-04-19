<x-layouts.app>

    <div class="mb-4">
        <h1 class="text-xl md:text-2xl font-semibold text-zinc-900 dark:text-white tracking-tight">Audit Logs</h1>
        <p class="text-sm text-zinc-500 mt-1">{{ $logs->total() }} catatan</p>
    </div>

    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 px-4 py-3 rounded-xl border text-sm font-medium bg-emerald-50 border-emerald-200 text-emerald-700 dark:bg-emerald-950/30 dark:border-emerald-800/60 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 flex items-center gap-3 px-4 py-3 rounded-xl border text-sm font-medium bg-red-50 border-red-200 text-red-700 dark:bg-red-950/30 dark:border-red-800/60 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <form method="GET" action="" class="flex items-center gap-3 w-full md:w-auto">
            <input type="search" name="q" value="{{ $q ?? '' }}" placeholder="Cari message atau meta..."
                   class="px-4 py-2 text-sm rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 focus:outline-none" />
            <select name="action" onchange="this.form.submit()" class="px-4 py-2 text-sm rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 focus:outline-none">
                <option value="">Semua tindakan</option>
                <option value="create" {{ (isset($action) && $action === 'create') ? 'selected' : '' }}>Create</option>
                <option value="update" {{ (isset($action) && $action === 'update') ? 'selected' : '' }}>Update</option>
                <option value="delete" {{ (isset($action) && $action === 'delete') ? 'selected' : '' }}>Delete</option>
                <option value="login" {{ (isset($action) && $action === 'login') ? 'selected' : '' }}>Login</option>
                <option value="logout" {{ (isset($action) && $action === 'logout') ? 'selected' : '' }}>Logout</option>
            </select>
            <select name="user_id" onchange="this.form.submit()" class="px-4 py-2 text-sm rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 focus:outline-none">
                <option value="">Semua user</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ (isset($userId) && $userId == $u->id) ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white">Filter</button>
        </form>
        <div class="w-full md:w-auto flex justify-start md:justify-end">
            <button type="button" data-modal-open data-modal-target="#logs-delete-modal" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg bg-red-600 hover:bg-red-700 active:bg-red-800 text-white transition-colors shadow-sm">
                Hapus Log
            </button>
        </div>
    </div>

    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/70 dark:bg-zinc-800/40">
                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-zinc-400 w-16">ID</th>
                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-zinc-400">User</th>
                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-zinc-400">Tindakan</th>
                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-zinc-400">Message</th>
                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-zinc-400">Meta</th>
                        <th class="px-4 py-3 text-right text-[11px] font-semibold uppercase tracking-widest text-zinc-400">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-zinc-800/60">
                    @foreach($logs as $log)
                    <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/30 transition-colors group">
                        <td class="px-4 py-3.5 font-mono text-[11px] text-zinc-400">#{{ $log->id }}</td>
                        <td class="px-4 py-3.5">{{ $log->user ? $log->user->name : '—' }}</td>
                        <td class="px-4 py-3.5">
                            @php
                                $badgeClass = 'bg-zinc-100 text-zinc-700';
                                if ($log->action === 'create') $badgeClass = 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300';
                                if ($log->action === 'update') $badgeClass = 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300';
                                if ($log->action === 'delete') $badgeClass = 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300';
                                if ($log->action === 'login')  $badgeClass = 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[12px] font-semibold {{ $badgeClass }}">{{ ucfirst($log->action) }}</span>
                        </td>
                        <td class="px-4 py-3.5">{{ $log->message }}</td>
                        <td class="px-4 py-3.5 text-[12px] text-zinc-400"><pre class="whitespace-pre-wrap">{{ json_encode($log->meta) }}</pre></td>
                        <td class="px-4 py-3.5 text-right text-[12px] text-zinc-400">{{ $log->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @endforeach

                    @if($logs->isEmpty())
                    <tr>
                        <td colspan="6" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="size-12 rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                                    <svg class="size-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Belum ada catatan</p>
                                <p class="text-xs text-zinc-400">Aktivitas akan tercatat otomatis saat pengguna melakukan tindakan</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="px-4 py-3 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
            <p class="text-xs text-zinc-400">Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }}</p>
            <div class="text-xs text-zinc-400">{{ $logs->links() }}</div>
        </div>
        @endif
    </div>

    <x-modal id="user-modal" title="Detail" />
    <x-modal id="photo-modal" title="Foto" size="lg" />

    <x-modal id="logs-delete-modal" title="Hapus Log">
        <form method="POST" action="{{ route('logs.destroyBulk') }}">
            @csrf
            <div class="space-y-4">
                <p class="text-sm text-zinc-500">Pilih tindakan hapus:</p>
                <div class="space-y-2">
                    <label class="inline-flex items-center gap-2"><input type="radio" name="mode" value="all" checked> Hapus semua</label><br>
                    <label class="inline-flex items-center gap-2"><input type="radio" name="mode" value="today"> Hapus hari ini</label><br>
                    <label class="inline-flex items-center gap-2"><input type="radio" name="mode" value="last_month"> Hapus bulan lalu</label><br>
                    <label class="inline-flex items-center gap-2"><input type="radio" name="mode" value="except_current_month"> Hapus selain bulan ini (pertahankan bulan ini)</label>
                </div>

                <div>
                    <p class="text-xs text-zinc-500">Ketik <strong>KONFIRMASI</strong> untuk melanjutkan:</p>
                    <input type="text" name="confirm_text" id="logs-confirm-input" class="w-full px-3 py-2 border rounded mt-1" placeholder="Ketik KONFIRMASI" required>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <button type="button" data-modal-close class="px-4 py-2 rounded border">Batal</button>
                    <button type="submit" id="logs-delete-submit" class="px-4 py-2 rounded bg-red-600 text-white" disabled>Hapus</button>
                </div>
            </div>
        </form>
    </x-modal>

@push('scripts')
<script>
document.addEventListener('input', function(e){
    const input = document.getElementById('logs-confirm-input');
    const btn = document.getElementById('logs-delete-submit');
    if (!input || !btn) return;
    btn.disabled = input.value.toUpperCase() !== 'KONFIRMASI';
});
// Replace JSON "null"/empty meta with a dash for readability
    document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('td pre.whitespace-pre-wrap').forEach(function(pre){
        const txt = (pre.textContent || '').trim();
        if (txt === 'null' || txt === '[]' || txt === '{}' || txt === '""' || txt === '' || txt === '-') {
            // hide the dash/empty meta entirely in the table cell
            pre.textContent = '';
        }
    });
});
</script>
@endpush

</x-layouts.app>
