
document.addEventListener('DOMContentLoaded', function () {
    const copyButtons = document.querySelectorAll(
        '.js-copy-meeting'
    );

    copyButtons.forEach(function (button) {
        button.addEventListener('click', async function () {
            const link = button.dataset.link;

            const feedback = button
                .closest('.psy-meeting-saved')
                ?.querySelector('.psy-meeting-copy-feedback');

            if (!link) return;

            try {
                await navigator.clipboard.writeText(link);

                if (feedback) {
                    feedback.textContent = 'Link meeting berhasil disalin.';
                }

            } catch (error) {
                if (feedback) {
                    feedback.textContent =
                        'Gagal menyalin link. Silakan salin secara manual.';
                }
            }
        });
    });
});
