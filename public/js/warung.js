document.addEventListener('DOMContentLoaded', () => {
    const formatRupiah = (angka) => {
        const nilai = Number.isFinite(angka) ? Math.round(angka) : 0;
        const prefix = nilai < 0 ? '-Rp ' : 'Rp ';
        return prefix + Math.abs(nilai).toLocaleString('id-ID');
    };

    // 1. Form Catat Penjualan (preset 1-5, -/+, ubah harga satuan, hitung total)
    document.querySelectorAll('[data-penjualan-form]').forEach((form) => {
        const inputPorsi = form.querySelector('[data-input-porsi]');
        const inputHarga = form.querySelector('[data-input-harga]');
        const inputHargaManual = form.querySelector('[data-input-harga-manual]');
        const labelHargaHeader = form.querySelector('[data-label-harga-header]');
        const tampilanPorsi = form.querySelector('[data-tampilan-porsi]');
        const tampilanTotal = form.querySelector('[data-total-tagihan]');
        const tampilanRincian = form.querySelector('[data-rincian-tagihan]');
        const panelHarga = form.querySelector('[data-panel-harga]');
        const btnToggleHarga = form.querySelector('[data-toggle-harga]');
        const btnTutupHarga = form.querySelector('[data-tutup-harga]');
        const btnSimpanHarga = form.querySelector('[data-simpan-harga]');
        const quickPorsiBtns = form.querySelectorAll('[data-porsi-preset]');
        const quickHargaBtns = form.querySelectorAll('[data-harga-preset]');
        const metodeRadios = form.querySelectorAll('[data-metode-radio]');

        const ambilMetode = () => {
            const checked = form.querySelector('[data-metode-radio]:checked');
            return checked ? checked.value.toUpperCase() : '';
        };

        const perbaruiTampilan = () => {
            const porsi = Math.max(0, parseInt(inputPorsi?.value, 10) || 0);
            const harga = Math.max(0, parseInt(inputHarga?.value, 10) || 0);
            const total = porsi * harga;
            const metode = ambilMetode();

            if (tampilanPorsi) {
                tampilanPorsi.textContent = `${porsi} Porsi`;
            }
            if (tampilanTotal) {
                tampilanTotal.textContent = formatRupiah(total);
            }
            if (tampilanRincian) {
                tampilanRincian.textContent = `${porsi} porsi × ${formatRupiah(harga)}${metode ? ' · ' + metode : ''}`;
            }
            if (labelHargaHeader) {
                labelHargaHeader.textContent = `Harga: ${formatRupiah(harga)}/porsi`;
            }

            quickPorsiBtns.forEach((btn) => {
                const nilai = parseInt(btn.getAttribute('data-porsi-preset'), 10);
                const terpilih = nilai === porsi;
                btn.classList.toggle('is-selected', terpilih);
                btn.setAttribute('aria-pressed', terpilih ? 'true' : 'false');
            });

            quickHargaBtns.forEach((btn) => {
                const nilai = parseInt(btn.getAttribute('data-harga-preset'), 10);
                btn.classList.toggle('bg-[#14532D]', false);
                btn.classList.toggle('is-active-harga', nilai === harga);
                if (nilai === harga) {
                    btn.style.backgroundColor = '#14532D';
                    btn.style.borderColor = '#14532D';
                    btn.style.color = '#FFFFFF';
                } else {
                    btn.style.backgroundColor = '#FFFFFF';
                    btn.style.borderColor = '#D6D0C4';
                    btn.style.color = '#1C1917';
                }
            });
        };

        const setPorsi = (nilaiBaru) => {
            if (!inputPorsi) return;
            inputPorsi.value = String(Math.max(0, nilaiBaru));
            perbaruiTampilan();
        };

        quickPorsiBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                setPorsi(parseInt(btn.getAttribute('data-porsi-preset'), 10) || 0);
            });
        });

        form.querySelector('[data-porsi-kurang]')?.addEventListener('click', () => {
            const sekarang = parseInt(inputPorsi?.value, 10) || 0;
            setPorsi(sekarang - 1);
        });

        form.querySelector('[data-porsi-tambah]')?.addEventListener('click', () => {
            const sekarang = parseInt(inputPorsi?.value, 10) || 0;
            setPorsi(sekarang + 1);
        });

        btnToggleHarga?.addEventListener('click', () => {
            if (!panelHarga) return;
            const tersembunyi = panelHarga.classList.toggle('d-none');
            btnToggleHarga.setAttribute('aria-expanded', tersembunyi ? 'false' : 'true');
            if (!tersembunyi && inputHargaManual && inputHarga) {
                inputHargaManual.value = inputHarga.value;
            }
        });

        btnTutupHarga?.addEventListener('click', () => {
            panelHarga?.classList.add('d-none');
            btnToggleHarga?.setAttribute('aria-expanded', 'false');
        });

        quickHargaBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                const nilai = parseInt(btn.getAttribute('data-harga-preset'), 10) || 15000;
                if (inputHarga) inputHarga.value = String(nilai);
                if (inputHargaManual) inputHargaManual.value = String(nilai);
                panelHarga?.classList.add('d-none');
                btnToggleHarga?.setAttribute('aria-expanded', 'false');
                perbaruiTampilan();
            });
        });

        btnSimpanHarga?.addEventListener('click', () => {
            const raw = (inputHargaManual?.value || '').replace(/[^\d]/g, '');
            const angka = parseInt(raw, 10);
            if (Number.isFinite(angka) && angka >= 1000 && inputHarga) {
                inputHarga.value = String(angka);
                if (inputHargaManual) inputHargaManual.value = String(angka);
                panelHarga?.classList.add('d-none');
                btnToggleHarga?.setAttribute('aria-expanded', 'false');
                perbaruiTampilan();
            }
        });

        inputHarga?.addEventListener('input', perbaruiTampilan);
        metodeRadios.forEach((radio) => radio.addEventListener('change', perbaruiTampilan));
        perbaruiTampilan();
    });

    // 2. Form Catat Kas (Pengeluaran vs Pemasukan Tambahan + Nominal Cepat + Keterangan Cepat)
    document.querySelectorAll('[data-kas-form]').forEach((form) => {
        const jenisRadios = form.querySelectorAll('[data-kas-jenis]');
        const inputNominal = form.querySelector('[data-input-nominal]');
        const inputKeterangan = form.querySelector('[data-input-keterangan]');
        const btnKosongkan = form.querySelector('[data-nominal-reset]');
        const btnSubmit = form.querySelector('[data-kas-submit]');
        const labelSubmit = form.querySelector('[data-kas-submit-label]');
        const grupPengeluaran = form.querySelector('[data-chips-pengeluaran]');
        const grupPemasukan = form.querySelector('[data-chips-pemasukan]');
        const quickNominalBtns = form.querySelectorAll('[data-nominal-set]');
        const quickKetBtns = form.querySelectorAll('[data-keterangan-preset]');

        const syncJenis = () => {
            const checked = form.querySelector('[data-kas-jenis]:checked');
            const jenis = checked ? checked.value : 'pengeluaran';
            const actionUrl = checked?.getAttribute('data-action-url');

            if (actionUrl) {
                form.setAttribute('action', actionUrl);
            }

            if (grupPengeluaran && grupPemasukan) {
                grupPengeluaran.classList.toggle('d-none', jenis !== 'pengeluaran');
                grupPemasukan.classList.toggle('d-none', jenis !== 'pemasukan');
            }

            if (btnSubmit) {
                btnSubmit.classList.toggle('is-pengeluaran', jenis === 'pengeluaran');
                btnSubmit.classList.toggle('is-pemasukan', jenis === 'pemasukan');
            }

            if (labelSubmit) {
                labelSubmit.textContent = jenis === 'pengeluaran'
                    ? 'Simpan Pengeluaran'
                    : 'Simpan Pemasukan';
            }
        };

        const syncNominal = () => {
            const nilai = parseInt(inputNominal?.value, 10) || 0;
            if (btnKosongkan) {
                btnKosongkan.classList.toggle('d-none', !inputNominal?.value);
            }
            quickNominalBtns.forEach((btn) => {
                const preset = parseInt(btn.getAttribute('data-nominal-set'), 10);
                const terpilih = nilai > 0 && nilai === preset;
                btn.classList.toggle('is-selected', terpilih);
                btn.setAttribute('aria-pressed', terpilih ? 'true' : 'false');
            });
        };

        const syncKeterangan = () => {
            const teks = inputKeterangan?.value || '';
            quickKetBtns.forEach((btn) => {
                const preset = btn.getAttribute('data-keterangan-preset') || '';
                btn.classList.toggle('is-selected', teks !== '' && teks === preset);
            });
        };

        jenisRadios.forEach((radio) => radio.addEventListener('change', syncJenis));

        quickNominalBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                if (!inputNominal) return;
                inputNominal.value = btn.getAttribute('data-nominal-set') || '';
                syncNominal();
            });
        });

        btnKosongkan?.addEventListener('click', () => {
            if (!inputNominal) return;
            inputNominal.value = '';
            syncNominal();
        });

        quickKetBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                if (!inputKeterangan) return;
                inputKeterangan.value = btn.getAttribute('data-keterangan-preset') || '';
                syncKeterangan();
            });
        });

        inputNominal?.addEventListener('input', syncNominal);
        inputKeterangan?.addEventListener('input', syncKeterangan);

        syncJenis();
        syncNominal();
        syncKeterangan();
    });

    // 3. Konfirmasi Inline 2-Langkah (Hapus Item & Reset Data)
    document.querySelectorAll('[data-inline-confirm]').forEach((container) => {
        const trigger = container.querySelector('[data-confirm-trigger]');
        const box = container.querySelector('[data-confirm-box]');
        const cancel = container.querySelector('[data-confirm-cancel]');

        trigger?.addEventListener('click', () => {
            trigger.classList.add('d-none');
            box?.classList.remove('d-none');
        });

        cancel?.addEventListener('click', () => {
            box?.classList.add('d-none');
            trigger?.classList.remove('d-none');
        });
    });
});
