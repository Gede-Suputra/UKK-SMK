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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        // stop any camera stream inside modal before clearing content
        const video = el.querySelector('video#camera-video');
        if (video && video.srcObject) {
            try { video.srcObject.getTracks().forEach(t => t.stop()); } catch(e){}
            video.srcObject = null;
        }
        if (body) { body.scrollTop = 0; body.innerHTML = ''; }
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
                // support opening a modal with a direct photo source (no ajax)
                const photoSrc = open.getAttribute('data-photo-src');
                if (photoSrc) {
                    modal.querySelector('[data-modal-body]').innerHTML = '<div class="p-4 flex items-center justify-center"><img src="' + photoSrc + '" class="max-h-[80vh] w-auto object-contain rounded-lg" alt="Foto"></div>';
                    showModal(modal);
                } else {
                    showModal(modal);
                }
            }
        }

        if (e.target.closest('[data-modal-close]') || e.target.closest('[data-modal-backdrop]')) {
            const modal = e.target.closest('[data-modal]');
            if (modal) hideModal(modal);
        }
    });

    // Form Submit (hanya untuk modal edit/create) + prevent double-submits
    document.addEventListener('submit', function(e){
        const form = e.target.closest('form');
        if (!form) return;

        const modal = form.closest('[data-modal]');

        // If the form is outside modal and has data-confirm, let the global confirm handler handle it
        if (!modal) return;

        e.preventDefault();

        // Client-side validation for Alat form
        if (form.id === 'alat-form') {
            const total = parseInt(form.querySelector('input[name="jumlah_total"]')?.value || '0', 10);
            const dipinjam = parseInt(form.querySelector('input[name="jumlah_dipinjam"]')?.value || '0', 10);
            const rusak = parseInt(form.querySelector('input[name="jumlah_rusak"]')?.value || '0', 10);
            if (isNaN(total) || total < 0) { alert('Jumlah total tidak boleh negatif'); form.dataset.submitting = ''; return; }
            if (dipinjam < 0 || dipinjam > total) { alert('Jumlah dipinjam harus antara 0 dan jumlah total'); form.dataset.submitting = ''; return; }
            if (rusak < 0 || rusak > total) { alert('Jumlah rusak harus antara 0 dan jumlah total'); form.dataset.submitting = ''; return; }
        }

        // prevent double submit
        if (form.dataset.submitting) return;
        form.dataset.submitting = '1';

        const fd = new FormData(form);
        const action = form.action;
        const methodInput = form.querySelector('input[name="_method"]');
        const fetchMethod = methodInput ? 'POST' : (form.method || 'POST');
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch(action, {
            method: fetchMethod.toUpperCase(),
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
                form.dataset.submitting = '';
                alert(data?.message || 'Terjadi kesalahan');
            }
        })
        .catch(() => {
            form.dataset.submitting = '';
            alert('Gagal menyimpan');
        });
    });

    // Global delegated confirmation for forms with data-confirm (works for delete forms in index views)
    document.addEventListener('submit', function(e){
        const form = e.target.closest('form[data-confirm]');
        if (!form) return;
        // if the form is inside a modal, the modal handler already handles it
        if (form.closest('[data-modal]')) return;
        e.preventDefault();
        const msg = form.getAttribute('data-confirm') || 'Yakin ingin melanjutkan?';
        if (typeof Swal === 'undefined') {
            if (!confirm(msg)) return; form.submit(); return;
        }
        Swal.fire({
            title: msg,
            text: 'Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            borderRadius: '12px',
        }).then((result) => { if (result.isConfirmed) form.submit(); });
    });

    // Delegated file input preview for profile photo
    document.addEventListener('change', function(e){
        const input = e.target.closest('input[type="file"][name="profile_photo_path"]');
        if (!input) return;
        const modal = input.closest('[data-modal]');
        const preview = modal ? modal.querySelector('#photo-preview') : document.getElementById('photo-preview');
        if (!preview) return;

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(ev){
                preview.innerHTML = `<img src="${ev.target.result}" class="w-full h-full object-cover">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    });

    // Delegated show/hide password toggle for dynamic modal content
    document.addEventListener('click', function(e){
        const btn = e.target.closest('[data-toggle-pwd]');
        if (!btn) return;
        e.preventDefault();
        const targetId = btn.getAttribute('data-toggle-pwd');
        const input = document.getElementById(targetId);
        if (!input) return;
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';

        const eyeSvg = '<svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">\
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>\
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>\
        </svg>';
        const eyeOffSvg = '<svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">\
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18"/>\
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.94 10.94A3 3 0 0113.06 13.06"/>\
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c1.16 0 2.273.187 3.313.53"/>\
        </svg>';

        // swap icon
        btn.innerHTML = isPassword ? eyeOffSvg : eyeSvg;
    });

    // Camera capture: delegated handlers so AJAX-injected forms work
    let _modalCameraStream = null;

    function openCameraInModal(modal) {
        const cameraModal = modal.querySelector('#camera-modal');
        const video = modal.querySelector('#camera-video');
        if (!cameraModal || !video || !navigator.mediaDevices) return alert('Kamera tidak tersedia.');
        cameraModal.classList.remove('hidden');
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false })
            .then(stream => {
                _modalCameraStream = stream;
                video.srcObject = stream;
                video.play();
            })
            .catch(err => {
                cameraModal.classList.add('hidden');
                alert('Tidak dapat mengakses kamera: ' + (err.message || err));
            });
    }

    function closeCameraInModal(modal) {
        const cameraModal = modal.querySelector('#camera-modal');
        const video = modal.querySelector('#camera-video');
        if (video) {
            try {
                video.pause();
                if (video.srcObject) {
                    video.srcObject.getTracks().forEach(t => t.stop());
                    video.srcObject = null;
                }
            } catch(e){}
        }
        _modalCameraStream = null;
        if (cameraModal) cameraModal.classList.add('hidden');
    }

    function captureImageInModal(modal) {
        const video = modal.querySelector('#camera-video');
        if (!video) return;
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth || 640;
        canvas.height = video.videoHeight || 480;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        canvas.toBlob(function(blob) {
            if (!blob) return alert('Gagal mengambil foto.');
            const file = new File([blob], 'capture.jpg', { type: 'image/jpeg' });
            const input = modal.querySelector('input[type="file"][name="profile_photo_path"]');
            if (input) {
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                // update preview inside modal
                const preview = modal.querySelector('#photo-preview');
                const reader = new FileReader();
                reader.onload = function(e){
                    if (preview) {
                        preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    }
                }
                reader.readAsDataURL(file);
            }
            closeCameraInModal(modal);
        }, 'image/jpeg', 0.95);
    }

    document.addEventListener('click', function(e){
        const take = e.target.closest('#take-photo-btn');
        if (take) {
            const modal = take.closest('[data-modal]') || document.querySelector('[data-modal].flex') || document;
            const targetModal = modal.closest('[data-modal]') || modal;
            if (targetModal && targetModal.querySelector) openCameraInModal(targetModal);
        }

        const close = e.target.closest('#close-camera-btn');
        if (close) {
            const modal = close.closest('[data-modal]');
            if (modal) closeCameraInModal(modal);
        }

        const cap = e.target.closest('#capture-btn');
        if (cap) {
            const modal = cap.closest('[data-modal]');
            if (modal) captureImageInModal(modal);
        }
    });
})();
</script>
@endpush
@endonce