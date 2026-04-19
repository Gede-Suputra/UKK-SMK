@props(['id' => 'modal', 'title' => '', 'size' => 'md'])

<div id="{{ $id }}" 
     {{ $attributes->merge(['class' => 'fixed inset-0 z-50 hidden items-center justify-center p-4']) }} 
     data-modal>

    <div class="absolute inset-0 bg-black/60 dark:bg-black/80" data-modal-backdrop></div>

    <div class="relative bg-white dark:bg-zinc-900 rounded-2xl shadow-xl 
                w-full {{ $size === 'lg' ? 'max-w-3xl' : 'max-w-2xl' }} 
                max-h-[94vh] flex flex-col overflow-hidden">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b dark:border-zinc-700 flex-shrink-0">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
            <button type="button" data-modal-close 
                    class="text-3xl leading-none text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div data-modal-body 
             class="flex-1 overflow-y-auto p-5 md:p-6 custom-scrollbar">
            {{ $slot }}
        </div>

    </div>
</div>

@once
@push('scripts')
<script>
(function(){
    function showModal(el){
        el.classList.remove('hidden');
        el.classList.add('flex');
    }

    function hideModal(el){
        el.classList.remove('flex');
        el.classList.add('hidden');
        const body = el.querySelector('[data-modal-body]');
        if (body) body.scrollTop = 0;
    }

    document.addEventListener('click', function(e){
        const open = e.target.closest('[data-modal-open]');
        if (open) {
            e.preventDefault();
            const targetSel = open.getAttribute('data-modal-target');
            const url = open.getAttribute('data-url');
            const modal = document.querySelector(targetSel);
            if (!modal) return;

            if (url) {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token }, credentials: 'same-origin' })
                    .then(r => r.text())
                    .then(html => {
                        modal.querySelector('[data-modal-body]').innerHTML = html;
                        showModal(modal);
                    })
                    .catch(() => alert('Gagal memuat data'));
            } else {
                showModal(modal);
            }
        }

        if (e.target.closest('[data-modal-close]') || e.target.closest('[data-modal-backdrop]')) {
            const modal = e.target.closest('[data-modal]');
            if (modal) hideModal(modal);
        }
    });

    // Form Submit (hanya untuk modal edit/create)
    document.addEventListener('submit', function(e){
        const form = e.target.closest('form');
        if (!form) return;
        const modal = form.closest('[data-modal]');
        if (!modal) return;

        e.preventDefault();
        const fd = new FormData(form);
        const action = form.action;
        const method = form.querySelector('input[name="_method"]')?.value || form.method || 'POST';
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch(action, {
            method: method.toUpperCase(),
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token },
            credentials: 'same-origin'
        })
        .then(r => r.json())
        .then(data => {
            if (data?.success) {
                hideModal(modal);
                location.reload();
            } else {
                alert(data?.message || 'Terjadi kesalahan');
            }
        })
        .catch(() => alert('Gagal menyimpan'));
    });
})();
</script>
@endpush
@endonce