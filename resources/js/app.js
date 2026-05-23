document.addEventListener('DOMContentLoaded', () => {
    const dismissToast = (toast) => {
        if (!toast || toast.dataset.dismissed === 'true') {
            return;
        }

        toast.dataset.dismissed = 'true';
        toast.classList.add('toast-leaving');

        window.setTimeout(() => {
            toast.remove();
        }, 320);
    };

    document.querySelectorAll('[data-toast]').forEach((toast) => {
        const timer = window.setTimeout(() => dismissToast(toast), 5000);

        toast.querySelector('[data-toast-close]')?.addEventListener('click', () => {
            window.clearTimeout(timer);
            dismissToast(toast);
        });
    });
});
