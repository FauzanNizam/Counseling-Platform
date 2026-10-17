
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('bookingForm');

    if (!form) {
        return;
    }

    const tipeSesi = document.getElementById('tipe_sesi');
    const pickupSection = document.getElementById('pickupSection');
    const titikJemput = document.getElementById('titik_jemput');
    const titikTujuan = document.getElementById('titik_tujuan');
    const jadwalSelect = document.getElementById('id_jadwal');
    const tanggalInput = document.getElementById('tanggal');
    const submitButton = document.getElementById('bookingSubmit');

    function updatePickupSection() {
        if (!tipeSesi || !pickupSection) {
            return;
        }

        const isPickup = tipeSesi.value === 'Pick-up';

        pickupSection.hidden = !isPickup;

        if (titikJemput) {
            titikJemput.required = isPickup;
            titikJemput.disabled = !isPickup;
        }

        if (titikTujuan) {
            titikTujuan.required = isPickup;
            titikTujuan.disabled = !isPickup;
        }
    }

    function normalizeDay(day) {
        const days = {
            'minggu': 0,
            'ahad': 0,
            'senin': 1,
            'selasa': 2,
            'rabu': 3,
            'kamis': 4,
            'jumat': 5,
            "jum'at": 5,
            'jumat ': 5,
            'sabtu': 6
        };

        const normalized = String(day)
            .trim()
            .toLowerCase()
            .replace(/\./g, '');

        return Object.prototype.hasOwnProperty.call(days, normalized)
            ? days[normalized]
            : null;
    }

    function validateDate() {
        if (!jadwalSelect || !tanggalInput) {
            return true;
        }

        tanggalInput.setCustomValidity('');

        const selectedOption =
            jadwalSelect.options[jadwalSelect.selectedIndex];

        if (
            !selectedOption ||
            !selectedOption.value ||
            !tanggalInput.value
        ) {
            return true;
        }

        // Format opsi:
        // Senin (09:00 – 12:00)
        const optionText = selectedOption.textContent.trim();
        const dayName = optionText.split('(')[0].trim();

        const expectedDay = normalizeDay(dayName);

        if (expectedDay === null) {
            // Validasi hari tetap harus dilakukan di server.
            return true;
        }

        const dateParts = tanggalInput.value.split('-').map(Number);

        if (dateParts.length !== 3) {
            return true;
        }

        const selectedDate = new Date(
            dateParts[0],
            dateParts[1] - 1,
            dateParts[2]
        );

        if (selectedDate.getDay() !== expectedDay) {
            tanggalInput.setCustomValidity(
                'Tanggal tidak sesuai dengan hari praktik yang dipilih (' +
                dayName +
                ').'
            );

            return false;
        }

        return true;
    }

    if (tipeSesi) {
        tipeSesi.addEventListener('change', updatePickupSection);
    }

    if (jadwalSelect) {
        jadwalSelect.addEventListener('change', validateDate);
    }

    if (tanggalInput) {
        tanggalInput.addEventListener('change', validateDate);
        tanggalInput.addEventListener('input', validateDate);
    }

    form.addEventListener('submit', function (event) {
        updatePickupSection();

        if (!validateDate() || !form.checkValidity()) {
            event.preventDefault();
            form.reportValidity();
            return;
        }

        if (submitButton) {
            submitButton.disabled = true;

            submitButton.innerHTML =
                '<i class="bi bi-hourglass-split" aria-hidden="true"></i>' +
                ' Mengirim Pengajuan...';
        }
    });

    updatePickupSection();
    validateDate();
});
