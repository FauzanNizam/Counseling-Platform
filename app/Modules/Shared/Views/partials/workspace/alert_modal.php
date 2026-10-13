
<!-- GLOBAL ALERT MODAL -->
<div
    class="modal fade"
    id="appAlertModal"
    tabindex="-1"
    aria-labelledby="appAlertTitle"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content app-alert-card">

            <div class="modal-body text-center">

                <div id="appAlertIcon" class="app-alert-icon">
                    !
                </div>

                <h3 id="appAlertTitle" class="app-alert-title">
                    Perhatian!
                </h3>

                <p id="appAlertMessage" class="app-alert-message">
                    Silakan periksa kembali data Anda.
                </p>

                <div class="app-alert-actions">
                    <button
                        type="button"
                        id="appAlertCancel"
                        class="app-alert-btn app-alert-btn-cancel"
                        data-bs-dismiss="modal"
                        hidden
                    >
                        Batal
                    </button>

                    <button
                        type="button"
                        id="appAlertConfirm"
                        class="app-alert-btn app-alert-btn-primary"
                    >
                        Mengerti
                    </button>
                </div>

            </div>

        </div>
    </div>
</div>
