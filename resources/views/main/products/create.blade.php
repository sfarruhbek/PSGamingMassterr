

@if(session('store'))
    <script>
        Swal.fire({
            title: "Yaratildi!",
            text: "Mahsulot muvaffaqiyatli qo'shildi",
            icon: "success"
        });
    </script>
@endif

<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mahsulot yaratish</h5>
                <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('res.product.store')}}">
                    @csrf
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="name" class="form-label">Mahsulot nomi</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Kiriting" required>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="count" class="form-label">Soni</label>
                            <input type="number" id="count" name="count" class="form-control" placeholder="Masalan: 100" min="1" required>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="income" class="form-label">Kirim narxi (sotib olinadigan narx, 1 tasi, so'mda)</label>
                            <input type="number" id="income" name="income" class="form-control" placeholder="Masalan: 4500" step="0.01" min="0" required>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="expense" class="form-label">Chiqim narxi (sotiladigan narx, 1 tasi, so'mda)</label>
                            <input type="number" id="expense" name="expense" class="form-control" placeholder="Masalan: 6000" step="0.01" min="0" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Yopish</button>
                        <button type="submit" class="btn btn-primary">Saqlash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

