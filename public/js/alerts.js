// Setup SweetAlert2 for Modern & Premium UI
// We use SweetAlert2 for both Modals and Toasts since the project is Vanilla JS (Blade).
// It has been styled to match the DESIGN.md (Vercel/Linear aesthetics, rounded corners, soft shadows).

const modernSwal = Swal.mixin({
    customClass: {
        popup: 'bg-surface border border-outline-variant/30 rounded-[24px] shadow-[0_12px_40px_-12px_rgba(0,0,0,0.1)] backdrop-blur-sm',
        title: 'text-[24px] font-bold text-on-surface font-sans tracking-tight mb-2',
        htmlContainer: 'text-[16px] text-on-surface-variant font-sans',
        confirmButton: 'bg-primary text-on-primary px-6 py-3 rounded-full text-[14px] font-bold hover:bg-primary/90 active:scale-95 transition-all shadow-sm',
        cancelButton: 'bg-surface-container-high text-on-surface px-6 py-3 rounded-full text-[14px] font-bold hover:bg-surface-container-highest active:scale-95 transition-all',
        actions: 'mt-6 gap-3',
        icon: 'border-0', // Remove borders from default icons
        closeButton: 'text-on-surface-variant hover:text-error transition-colors focus:outline-none'
    },
    buttonsStyling: false,
    showCloseButton: true
});

// Toast configuration mimicking Sonner
const Toast = Swal.mixin({
    toast: true,
    position: 'bottom-end',
    showConfirmButton: false,
    timer: 4000,
    timerProgressBar: false,
    customClass: {
        popup: 'bg-surface border border-outline-variant/20 rounded-[16px] shadow-[0_8px_30px_-8px_rgba(0,0,0,0.12)] flex items-center mb-4 mr-4',
        title: 'text-[14px] font-medium text-on-surface ml-2 font-sans',
        icon: 'border-0 scale-75 m-0',
    },
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

window.Alert = {
    // 1. Toast Alerts (Sonner-like)
    toast: {
        success: (message) => {
            Toast.fire({
                icon: 'success',
                iconHtml: '<span class="material-symbols-outlined text-primary text-[24px]">check_circle</span>',
                title: message,
            });
        },
        error: (message) => {
            Toast.fire({
                icon: 'error',
                iconHtml: '<span class="material-symbols-outlined text-error text-[24px]">error</span>',
                title: message,
            });
        },
        info: (message) => {
            Toast.fire({
                icon: 'info',
                iconHtml: '<span class="material-symbols-outlined text-primary text-[24px]">info</span>',
                title: message,
            });
        }
    },

    // 2. Modal Alerts
    modal: {
        success: (title, text) => {
            modernSwal.fire({
                icon: 'success',
                title: title,
                text: text,
                confirmButtonText: 'Lanjutkan',
            });
        },
        error: (title, text) => {
            modernSwal.fire({
                icon: 'error',
                title: title,
                text: text,
                confirmButtonText: 'Tutup',
            });
        },
        confirm: (title, text, confirmText = 'Ya, Lanjutkan', isDanger = false) => {
            return modernSwal.fire({
                icon: 'warning',
                title: title,
                text: text,
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal',
                customClass: {
                    ...modernSwal.params.customClass,
                    confirmButton: isDanger 
                        ? 'bg-error text-white px-6 py-3 rounded-full text-[14px] font-bold hover:bg-[#93000a] active:scale-95 transition-all shadow-sm'
                        : modernSwal.params.customClass.confirmButton
                }
            });
        }
    }
};
