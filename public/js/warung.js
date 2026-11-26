document.addEventListener('DOMContentLoaded', () => {
    const formatRupiah = (angka) => {
        const nilai = Math.max(0, parseInt(angka, 10) || 0);
        return 'Rp ' + nilai.toLocaleString('id-ID');
    };

    // 1. Helper Form Penjualan (tombol cepat porsi, stepper -/+, ubah harga, hitung total)
    document.querySelectorAll('[data-penjualan-form]').forEach((form) => {
        const inputPorsi = form.querySelector('[data-input-porsi]');
        const inputHarga = form.querySelector('[data-input-harga]');
        const tampilanTotal = form.querySelector('[data-total-bayar]');
        const tampilanRincian = form.querySelector('[data-rincian-hitung]');
        const quickButtons = form.querySelectorAll('[data-porsi-preset]');
        const btnKurang = form.querySelector('[data-porsi-kurang]');
        const btnTambah = form.querySelector('[data-porsi-tambah]');
        const btnToggleHarga = form.querySelector('[data-toggle-harga]');
        const boxHarga = form.querySelector('[data-box-harga]');
        const btnResetHarga = form.querySelector('[data-reset-harga]');

        const hitungTotal = () => {
            const porsi = parseInt(inputPorsi?.value, 10) || 0;
            const harga = parseInt(inputHarga?.value, 10) || 0;
            const total = porsi > 0 && harga > 0 ? porsi * harga : 0;

            if (tampilanTotal) {
                tampilanTotal.textContent = formatRupiah(total);
            }
            if (tampilanRincian) {
                tampilanRincian.textContent = porsi > 0
                    ? `${porsi} porsi x ${formatRupiah(harga)}`
                    : 'Pilih atau masukkan jumlah porsi';
            }

            quickButtons.forEach((btn) => {
                const nilaiBtn = parseInt(btn.getAttribute('data-porsi-preset'), 10);
                btn.classList.toggle('is-selected', nilaiBtn === porsi);
            });
        };

        quickButtons.forEach((btn) => {
            btn.addEventListener('click', () => {
                if (!inputPorsi) return;
                inputPorsi.value = btn.getAttribute('data-porsi-preset');
                hitungTotal();
            });
        });

        btnKurang?.addEventListener('click', () => {
            if (!inputPorsi) return;
            const sekarang = parseInt(inputPorsi.value, 10) || 0;
            inputPorsi.value = sekarang > 1 ? sekarang - 1 : 1;
            hitungTotal();
        });

        btnTambah?.addEventListener('click', () => {
            if (!inputPorsi) return;
            const sekarang = parseInt(inputPorsi.value, 10) || 0;
            inputPorsi.value = sekarang + 1;
            hitungTotal();
        });

        btnToggleHarga?.addEventListener('click', () => {
            if (!boxHarga) return;
            const tersembunyi = boxHarga.classList.toggle('d-none');
            btnToggleHarga.setAttribute('aria-expanded', tersembunyi ? 'false' : 'true');
            if (!tersembunyi) {
                inputHarga?.focus();
            }
        });

        btnResetHarga?.addEventListener('click', () => {
            if (!inputHarga) return;
            inputHarga.value = btnResetHarga.getAttribute('data-default-harga') || '15000';
            hitungTotal();
        });

        inputPorsi?.addEventListener('input', hitungTotal);
        inputHarga?.addEventListener('input', hitungTotal);
        hitungTotal();
    });

    // 2. Helper Form Pemasukan & Pengeluaran (tombol cepat nominal & keterangan)
    document.querySelectorAll('[data-kas-form]').forEach((form) => {
        const inputNominal = form.querySelector('[data-input-nominal]');
        const inputKeterangan = form.querySelector('[data-input-keterangan]');
        const previewNominal = form.querySelector('[data-nominal-preview]');

        const perbaruiPreviewNominal = () => {
            if (!previewNominal) return;
            const angka = parseInt(inputNominal?.value, 10) || 0;
            previewNominal.textContent = angka > 0 ? formatRupiah(angka) : 'Rp 0';
        };

        form.querySelectorAll('[data-nominal-set]').forEach((btn) => {
            btn.addEventListener('click', () => {
                if (!inputNominal) return;
                inputNominal.value = btn.getAttribute('data-nominal-set');
                perbaruiPreviewNominal();
            });
        });

        form.querySelectorAll('[data-nominal-add]').forEach((btn) => {
            btn.addEventListener('click', () => {
                if (!inputNominal) return;
                const sekarang = parseInt(inputNominal.value, 10) || 0;
                const tambah = parseInt(btn.getAttribute('data-nominal-add'), 10) || 0;
                inputNominal.value = sekarang + tambah;
                perbaruiPreviewNominal();
            });
        });

        form.querySelector('[data-nominal-reset]')?.addEventListener('click', () => {
            if (!inputNominal) return;
            inputNominal.value = '';
            perbaruiPreviewNominal();
        });

        form.querySelectorAll('[data-keterangan-preset]').forEach((btn) => {
            btn.addEventListener('click', () => {
                if (!inputKeterangan) return;
                inputKeterangan.value = btn.getAttribute('data-keterangan-preset');
            });
        });

        inputNominal?.addEventListener('input', perbaruiPreviewNominal);
        perbaruiPreviewNominal();
    });

    // 3. Modal Konfirmasi Hapus Data
    const modalHapus = document.getElementById('modalKonfirmasiHapus');
    if (modalHapus) {
        modalHapus.addEventListener('show.bs.modal', (event) => {
            const trigger = event.relatedTarget;
            if (!trigger) return;

            const actionUrl = trigger.getAttribute('data-delete-action') || '';
            const detailText = trigger.getAttribute('data-delete-detail') || 'Data terpilih';

            const formHapus = document.getElementById('formKonfirmasiHapus');
            const detailEl = document.getElementById('modalKonfirmasiHapusDetail');

            if (formHapus) {
                formHapus.setAttribute('action', actionUrl);
            }
            if (detailEl) {
                detailEl.textContent = detailText;
            }
        });
    }
});
