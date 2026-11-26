@if (session('success'))
    <div class="alert alert-success warung-alert shadow-sm d-flex align-items-center gap-3 mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-2 flex-shrink-0" aria-hidden="true"></i>
        <div class="fs-5 fw-semibold">{{ session('success') }}</div>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger warung-alert shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-start gap-3">
            <i class="bi bi-exclamation-octagon-fill fs-2 flex-shrink-0" aria-hidden="true"></i>
            <div>
                <div class="fs-5 fw-bold mb-1">Perhatian, periksa kembali isian Anda:</div>
                <ul class="mb-0 ps-3 fs-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
