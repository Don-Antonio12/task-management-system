{{-- Reusable Confirm and Alert Modals - included in app layout --}}
{{-- Confirm Modal: trigger via data-confirm-btn on button, data-confirm-title, data-confirm-message --}}
{{-- Error/Alert: use window.TMS.showErrorModal('message') or window.TMS.showAlertModal('title', 'message') --}}

<!-- Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="confirmModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    <span id="confirmModalTitle">Confirm</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <p id="confirmModalMessage" class="mb-0">Are you sure?</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmModalSubmit">
                    <i class="fas fa-trash me-1"></i> Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Alert/Error Modal -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="alertModalLabel">
                    <i class="fas fa-exclamation-circle text-danger me-2"></i>
                    <span id="alertModalTitle">Error</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <p id="alertModalMessage" class="mb-0"></p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    if (window.TMS) return;

    const confirmModalEl = document.getElementById('confirmModal');
    const alertModalEl = document.getElementById('alertModal');

    window.TMS = {
        showErrorModal: function(message) {
            this.showAlertModal('Error', message || 'Something went wrong.');
        },
        showAlertModal: function(title, message) {
            if (!alertModalEl) return;
            document.getElementById('alertModalTitle').textContent = title || 'Notice';
            document.getElementById('alertModalMessage').textContent = message || '';
            const modal = new bootstrap.Modal(alertModalEl);
            modal.show();
        },
        showConfirmModal: function(options, onConfirm) {
            if (!confirmModalEl) return;
            const title = options.title || 'Confirm';
            const message = options.message || 'Are you sure?';
            document.getElementById('confirmModalTitle').textContent = title;
            document.getElementById('confirmModalMessage').textContent = message;

            const submitBtn = document.getElementById('confirmModalSubmit');
            const modal = new bootstrap.Modal(confirmModalEl);

            function cleanup() {
                submitBtn.removeEventListener('click', handler);
                confirmModalEl.removeEventListener('hidden.bs.modal', cleanup);
            }

            function handler() {
                if (typeof onConfirm === 'function') onConfirm();
                modal.hide();
                cleanup();
            }

            submitBtn.addEventListener('click', handler);
            confirmModalEl.addEventListener('hidden.bs.modal', cleanup);
            modal.show();
        }
    };

    document.addEventListener('click', function(e) {
        const btn = e.target && e.target.closest ? e.target.closest('[data-confirm-btn]') : null;
        if (!btn) return;
        e.preventDefault();
        e.stopPropagation();
        const form = btn.closest('form');
        if (!form) return;
        const title = btn.getAttribute('data-confirm-title') || 'Confirm';
        const message = btn.getAttribute('data-confirm-message') || 'Are you sure?';
        TMS.showConfirmModal({ title: title, message: message }, function() {
            form.submit();
        });
    }, true);
})();
</script>
