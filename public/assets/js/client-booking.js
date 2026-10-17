
document.addEventListener('DOMContentLoaded', function () {

    const tipeSesi = document.getElementById('tipe_sesi');
    const pickupSection = document.getElementById('pickupSection');

    const titikJemput = document.getElementById('titik_jemput');
    const titikTujuan = document.getElementById('titik_tujuan');

    if (
        !tipeSesi ||
        !pickupSection ||
        !titikJemput ||
        !titikTujuan
    ) {
        return;
    }

    function togglePickup() {

        const isPickup = tipeSesi.value === 'Pick-up';

        // Tampilkan alamat hanya untuk Pick-up.
        pickupSection.hidden = !isPickup;

        // Alamat wajib diisi untuk Pick-up.
        titikJemput.required = isPickup;
        titikTujuan.required = isPickup;

        // Saat Online, alamat tidak dikirim.
        titikJemput.disabled = !isPickup;
        titikTujuan.disabled = !isPickup;
    }

    tipeSesi.addEventListener('change', togglePickup);

    // Menyesuaikan tampilan saat halaman pertama dibuka.
    togglePickup();

});
